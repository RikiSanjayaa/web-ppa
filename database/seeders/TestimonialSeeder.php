<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Ibu R',
                'relation' => 'Masyarakat',
                'content' => 'Pelayanan cepat dan petugas sangat empatik saat menerima laporan kami.',
                'rating' => 5,
            ],
            [
                'name' => 'Bapak S',
                'relation' => 'Orang Tua Korban',
                'content' => 'Proses pendampingan jelas dan setiap perkembangan kasus diinformasikan dengan baik.',
                'rating' => 5,
            ],
            [
                'name' => 'Saudari N',
                'relation' => 'Pelapor',
                'content' => 'Form aduan online memudahkan pelaporan awal sebelum datang ke kantor layanan.',
                'rating' => 4,
            ],
            [
                'name' => 'Ibu M',
                'relation' => 'Masyarakat',
                'content' => 'Koordinasi antarpihak cukup cepat dan kami merasa dibantu selama proses penanganan.',
                'rating' => 5,
            ],
            [
                'name' => 'Bapak H',
                'relation' => 'Pelapor',
                'content' => 'Hotline WhatsApp responsif dan ramah dalam memberikan arahan tindak lanjut.',
                'rating' => 4,
            ],
            [
                'name' => 'Ibu A',
                'relation' => 'Pendamping Keluarga',
                'content' => 'Pelayanan publiknya terstruktur, mulai dari aduan, verifikasi, hingga pendampingan.',
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::query()->updateOrCreate(
                [
                    'name' => $testimonial['name'],
                    'relation' => $testimonial['relation'],
                ],
                [
                    'content' => $testimonial['content'],
                    'rating' => $testimonial['rating'],
                    'display_order' => $index + 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
