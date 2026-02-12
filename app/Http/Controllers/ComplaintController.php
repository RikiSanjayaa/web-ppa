<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use App\Services\TurnstileVerifier;
use App\Services\WhatsAppComplaintMessage;
use App\Support\SiteDefaults;
use Illuminate\Validation\ValidationException;

class ComplaintController extends Controller
{
    public function store(
        StoreComplaintRequest $request,
        TurnstileVerifier $turnstileVerifier,
        WhatsAppComplaintMessage $whatsAppComplaintMessage
    ) {
        $token = $request->input('cf-turnstile-response');

        if (! $turnstileVerifier->passes($token, $request->ip())) {
            throw ValidationException::withMessages([
                'cf-turnstile-response' => 'Verifikasi keamanan gagal. Silakan coba kembali.',
            ]);
        }

        $validated = $request->safe()->except('cf-turnstile-response');

        $complaint = Complaint::query()->create([
            ...$validated,
            'status' => Complaint::STATUS_MASUK,
            'channel' => 'web',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        ComplaintStatusHistory::query()->create([
            'complaint_id' => $complaint->id,
            'to_status' => Complaint::STATUS_MASUK,
            'note' => 'Aduan dibuat dari web publik.',
        ]);

        $hotline = SiteSetting::getValue('hotline_wa_number', env('HOTLINE_WA_NUMBER'));

        if (! $hotline) {
            throw ValidationException::withMessages([
                'no_hp' => 'Nomor hotline belum dikonfigurasi oleh admin.',
            ]);
        }

        $message = $whatsAppComplaintMessage->build($complaint);
        $waUrl = $whatsAppComplaintMessage->toWaMeUrl($hotline, $message);

        $complaint->update([
            'wa_redirected_at' => now(),
        ]);

        ActivityLogger::log('complaint.created', $complaint, 'Aduan baru dikirim dari form publik.');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $waUrl,
                'message' => 'Aduan berhasil dibuat.',
            ]);
        }

        return view('public.complaint-success', [
            'waUrl' => $waUrl,
            'complaint' => $complaint,
            'settings' => SiteSetting::getMap(['site_name', 'hotline_wa_number'])->all() + SiteDefaults::values(),
        ]);
    }
}
