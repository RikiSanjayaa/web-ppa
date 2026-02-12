<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\SiteSetting;
use App\Services\WhatsAppConsultationMessage;
use App\Support\SiteDefaults;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request, WhatsAppConsultationMessage $whatsAppConsultationMessage)
    {
        $validated = $request->validate([
            'nama_klien' => ['required', 'string', 'max:255'],
            'permasalahan' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $consultation = Consultation::create([
            'nama_klien' => $validated['nama_klien'],
            'permasalahan' => $validated['permasalahan'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $hotline = SiteSetting::getValue('hotline_wa_number', env('HOTLINE_WA_NUMBER'));

        if (! $hotline) {
            return redirect()->back()->with('success', 'Konsultasi berhasil dikirim, namun nomor WhatsApp admin belum tersedia.');
        }

        $message = $whatsAppConsultationMessage->build($consultation);
        $waUrl = $whatsAppConsultationMessage->toWaMeUrl($hotline, $message);

        return view('public.consultation-success', [
            'waUrl' => $waUrl,
            'consultation' => $consultation,
            'settings' => SiteSetting::getMap(['site_name', 'hotline_wa_number'])->all() + SiteDefaults::values(),
        ]);
    }
}
