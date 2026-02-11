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
            'nama_klien' => 'required|string|max:255',
            'permasalahan' => 'required|string',
        ]);

        $consultation = Consultation::create($validated);

        $hotline = SiteSetting::getValue('hotline_wa_number', env('HOTLINE_WA_NUMBER'));

        if (!$hotline) {
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
