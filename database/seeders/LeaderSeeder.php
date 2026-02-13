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
                'bio' => 'Bertugas melaksanakan penyelidikan dan penyidikan tindak pidana kekerasan terhadap perempuan, anak, disabilitas, lanjut usia, buruh dan kelompok rentan lain serta pemberantasan perdagangan orang dan penyelundupan manusia.',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'KOMPOL PRATIWI NORIANI, S.H., S.I.K., M.M.',
                'position' => 'Kasubdit 2 PPA dan PPO',
                'bio' => 'Bertugas menangani tindak pidana yang terkait kekerasan terhadap anak',
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        $leaderNames = collect($leaders)->pluck('name')->toArray();

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

        // Hapus pimpinan yang tidak ada dalam daftar
        Leader::query()->whereNotIn('name', $leaderNames)->delete();
    }
}
