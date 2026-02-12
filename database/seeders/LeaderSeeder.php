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
                'name' => 'KOMBES POL Ni MADE PUJEWATI, S.IK., M.M.',
                'position' => 'Direktur Reskrim PPA dan PPO',
                'bio' => 'Bertugas melaksanakan penyelidikan dan
                    penyidikan tindak pidana kekerasan terhadap perempuan, anak,
                    disabilitas, lanjut usia, buruh dan kelompok rentan lain serta
                    pemberantasan perdagangan orang dan penyelundupan manusia.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'KOMPOL PRATIWI NORIANI, S.H., S.I.K., M.M.',
                'position' => 'Kasubdit 2 PPA dan PPO',
                'bio' => 'Bertugas menangani tindak pidana yang terkait
                kekerasan terhadap anak',
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
