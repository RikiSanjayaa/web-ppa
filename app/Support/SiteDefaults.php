<?php

namespace App\Support;

class SiteDefaults
{
    /**
     * @return array<string, string>
     */
    public static function values(): array
    {
        return [
            'site_name' => 'PPA / PPO',
            'hero_title' => 'Perlindungan Perempuan dan Anak',
            'hero_subtitle' => 'Layanan aduan cepat, aman, dan responsif untuk masyarakat.',
            'hotline_wa_number' => '6281234567890',
            'contact_phone' => '(021) 0000000',
            'contact_email' => 'ppa@example.go.id',
            'contact_address' => 'Alamat kantor layanan PPA/PPO',
            'organization_profile' => 'Unit PPA/PPO menangani aduan masyarakat terkait perlindungan perempuan dan anak.',
            'organization_vision' => 'Mewujudkan pelayanan perlindungan yang cepat, empatik, dan profesional.',
            'organization_mission' => "1. Memberikan layanan aduan terintegrasi.\n2. Menangani laporan secara tepat waktu.\n3. Menguatkan kolaborasi lintas instansi.",
            'organization_structure' => 'Struktur organisasi dapat diperbarui oleh admin.',
            'instagram_url' => '',
            'facebook_url' => '',
            'youtube_url' => '',
        ];
    }
}
