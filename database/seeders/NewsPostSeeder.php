<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Database\Seeders\Support\PlaceholderMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Sosialisasi Perlindungan Anak di Sekolah Menengah',
                'type' => 'event',
                'excerpt' => 'Program edukasi pencegahan kekerasan berbasis sekolah bekerja sama dengan guru dan orang tua.',
                'content' => "Kegiatan sosialisasi ini membahas pola pencegahan kekerasan terhadap anak, kanal aduan resmi, dan dukungan psikososial bagi korban.\n\nMasyarakat diimbau memanfaatkan hotline dan form aduan untuk pelaporan cepat.",
                'published_days_ago' => 3,
            ],
            [
                'title' => 'Pelatihan Petugas Layanan Aduan Berbasis Empati',
                'type' => 'berita',
                'excerpt' => 'Pelatihan internal untuk meningkatkan kualitas respons awal dan komunikasi publik.',
                'content' => "Petugas layanan dibekali standar operasional terbaru, teknik asesmen awal, serta etika perlindungan data pelapor.\n\nLangkah ini bertujuan mempercepat proses tindak lanjut aduan masyarakat.",
                'published_days_ago' => 8,
            ],
            [
                'title' => 'Forum Koordinasi Penanganan Kasus Perempuan dan Anak',
                'type' => 'event',
                'excerpt' => 'Koordinasi lintas unit dan mitra untuk percepatan proses rujukan korban.',
                'content' => "Forum membahas harmonisasi alur penanganan kasus, integrasi data, dan strategi peningkatan pemulihan korban.\n\nHasil forum akan menjadi acuan penguatan layanan triwulan berikutnya.",
                'published_days_ago' => 14,
            ],
            [
                'title' => 'Pembukaan Pos Konsultasi Hukum Keliling',
                'type' => 'berita',
                'excerpt' => 'Pos konsultasi hukum keliling hadir untuk memperluas jangkauan layanan masyarakat.',
                'content' => "Pos konsultasi keliling difokuskan pada daerah padat penduduk agar masyarakat dapat berkonsultasi lebih mudah.\n\nJadwal dan lokasi layanan diumumkan secara berkala melalui kanal resmi.",
                'published_days_ago' => 21,
            ],
            [
                'title' => 'Kampanye Digital Anti Kekerasan dan Perdagangan Orang',
                'type' => 'event',
                'excerpt' => 'Edukasi digital untuk meningkatkan kesadaran dan kewaspadaan masyarakat.',
                'content' => "Kampanye digital dilakukan melalui media sosial resmi dengan materi edukatif yang ramah publik.\n\nMateri meliputi tanda-tanda risiko, langkah pelaporan, dan dukungan bagi korban.",
                'published_days_ago' => 28,
            ],
            [
                'title' => 'Rilis Statistik Penanganan Aduan Semester I',
                'type' => 'berita',
                'excerpt' => 'Data statistik semester pertama sebagai bentuk transparansi pelayanan publik.',
                'content' => "Statistik mencakup jumlah aduan masuk, status tindak lanjut, dan waktu respons rata-rata.\n\nData ini dipakai untuk evaluasi mutu layanan dan penyusunan target perbaikan.",
                'published_days_ago' => 35,
            ],
        ];

        foreach ($posts as $index => $post) {
            $slug = Str::slug($post['title']);
            $publishedAt = now()->subDays($post['published_days_ago']);
            $imagePath = PlaceholderMedia::ensureImage('news/news-'.($index + 1).'.jpg');

            NewsPost::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $post['title'],
                    'type' => $post['type'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'featured_image_path' => $imagePath,
                    'is_published' => true,
                    'published_at' => $publishedAt,
                    'meta_title' => $post['title'].' | PPA/PPO',
                    'meta_description' => $post['excerpt'],
                ]
            );
        }
    }
}
