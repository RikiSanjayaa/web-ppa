<?php

namespace App\Services;

use App\Models\Complaint;

class WhatsAppComplaintMessage
{
    public function build(Complaint $complaint): string
    {
        $parts = [
            '*Aduan Baru PPA/PPO*',
            'Nama Pelapor: '.$complaint->nama_lengkap,
            'No HP: '.$complaint->no_hp,
            'Tempat Kejadian: '.$complaint->tempat_kejadian,
            'Waktu Kejadian: '.$complaint->waktu_kejadian?->format('d-m-Y H:i'),
            'Kronologis: '.$complaint->kronologis_singkat,
        ];

        $optional = [
            'NIK' => $complaint->nik,
            'Alamat' => $complaint->alamat,
            'Email' => $complaint->email,
            'Korban' => $complaint->korban,
            'Terlapor' => $complaint->terlapor,
            'Saksi-saksi' => $complaint->saksi_saksi,
        ];

        foreach ($optional as $label => $value) {
            if ($value) {
                $parts[] = $label.': '.$value;
            }
        }

        $parts[] = '---';
        $parts[] = 'Aduan ini sudah tersimpan di sistem web.';

        return implode("\n", $parts);
    }

    public function toWaMeUrl(string $phoneNumber, string $message): string
    {
        $phone = preg_replace('/\D+/', '', $phoneNumber) ?: '';

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
