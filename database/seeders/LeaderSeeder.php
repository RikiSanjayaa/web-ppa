<?php

namespace Database\Seeders;

use App\Models\Leader;
use Database\Seeders\Support\PlaceholderMedia;
use Illuminate\Database\Seeder;

class LeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaders = [
            [
                'name' => 'Kombes Pol. Rina Kartika',
                'position' => 'Direktur Reskrimsus',
                'bio' => 'Memimpin kebijakan penanganan perkara khusus, termasuk penguatan layanan perlindungan perempuan dan anak.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'AKBP Sari Wulandari',
                'position' => 'Kasubdit PPA/PPO',
                'bio' => 'Mengkoordinasikan tindak lanjut aduan dan kolaborasi lintas unit terkait perlindungan perempuan dan anak.',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Kompol Dwi Prasetyo',
                'position' => 'Kanit Penanganan Aduan',
                'bio' => 'Bertanggung jawab pada alur verifikasi dan pemutakhiran status aduan masyarakat.',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Kompol Ika Paramitha',
                'position' => 'Kanit Pendampingan Korban',
                'bio' => 'Fokus pada layanan pendampingan korban dan koordinasi dengan mitra psikososial.',
                'display_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($leaders as $index => $leader) {
            $photoPath = PlaceholderMedia::ensureImage('leaders/leader-'.($index + 1).'.jpg');

            Leader::query()->updateOrCreate(
                ['name' => $leader['name']],
                [
                    ...$leader,
                    'photo_path' => $photoPath,
                ]
            );
        }
    }
}
