<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use App\Services\TurnstileVerifier;
use App\Services\WhatsAppComplaintMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ComplaintController extends Controller
{
    public function store(
        StoreComplaintRequest $request,
        TurnstileVerifier $turnstileVerifier,
        WhatsAppComplaintMessage $whatsAppComplaintMessage
    ): RedirectResponse {
        $token = $request->input('cf-turnstile-response');

        if (! $turnstileVerifier->passes($token, $request->ip())) {
            throw ValidationException::withMessages([
                'cf-turnstile-response' => 'Verifikasi keamanan gagal. Silakan coba kembali.',
            ]);
        }

        $validated = $request->safe()->except('cf-turnstile-response');

        $complaint = Complaint::query()->create([
            ...$validated,
            'status' => Complaint::STATUS_BARU,
            'channel' => 'web',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        ComplaintStatusHistory::query()->create([
            'complaint_id' => $complaint->id,
            'to_status' => Complaint::STATUS_BARU,
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
            return redirect()->away($waUrl);
        }

        return redirect()->away($waUrl);
    }
}
