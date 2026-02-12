<?php

namespace App\Services;

use App\Models\Consultation;

class WhatsAppConsultationMessage
{
    public function build(Consultation $consultation): string
    {
        $parts = [
            '*Konsultasi Baru PPA/PPO*',
            'Nama Klien: '.$consultation->nama_klien,
            'Permasalahan: '.$consultation->permasalahan,
            '---',
            'Konsultasi ini sudah tersimpan di sistem web.',
        ];

        return implode("\n", $parts);
    }

    public function toWaMeUrl(string $phoneNumber, string $message): string
    {
        $phone = preg_replace('/\D+/', '', $phoneNumber) ?: '';

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
