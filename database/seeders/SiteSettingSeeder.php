<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Support\SiteDefaults;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = array_replace(SiteDefaults::values(), [
            'site_name' => 'DITRES PPA PPO POLDA NTB',
            'hero_title' => 'Perlindungan Perempuan dan Anak',
            'hero_subtitle' => 'Kanal aduan resmi, responsif, dan berorientasi pelayanan publik untuk perempuan dan anak.',
            'hotline_wa_number' => (string) env('HOTLINE_WA_NUMBER', '6281234567890'),
            'contact_phone' => '(021) 721-8000',
            'contact_email' => 'ppa-ppo@contoh.go.id',
            'contact_address' => 'Jl. Langko No.77, Taman Sari, Kec. Ampenan, Kota Mataram, Nusa Tenggara Barat. 83112',
            'organization_profile' => 'Ditres PPA & PPO memberikan layanan aduan, pendampingan, dan koordinasi penanganan kasus perlindungan perempuan serta anak.',
            'organization_vision' => 'Terwujudnya penegakkan hukum yang bersih, transparan dan berkeadilan, untuk terciptanya kepastian hukum dan memberikan rasa aman bagi masyarakat Nusa Tenggara Barat',
            'organization_mission' => "1. Mewujudkan kepastian hukum;\n2. Menciptakan rasa keadilan dengan menjunjung tinggi HAM;\n3. Penyelidikan dan penyidikan berbasis Ilmu Pengetahuan dan Teknologi.",
            'organization_structure' => 'Struktur organisasi dan pejabat dapat diperbarui langsung melalui panel admin.',
            'instagram_url' => 'https://instagram.com/ppapolri',
            'facebook_url' => 'https://facebook.com/ppapolri',
            'youtube_url' => 'https://youtube.com/@ppapolri',
            'tiktok_url' => 'https://tiktok.com/@ppapolri',
        ]);

        foreach ($settings as $key => $value) {
            SiteSetting::upsertValue($key, (string) $value);
        }
    }
}
