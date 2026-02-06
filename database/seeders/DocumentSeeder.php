<?php

namespace Database\Seeders;

use App\Models\Document;
use Database\Seeders\Support\PlaceholderMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'title' => 'Undang-Undang Perlindungan Anak',
                'number' => 'UU No. 35 Tahun 2014',
                'year' => 2014,
                'category' => 'Undang-Undang',
                'summary' => 'Perubahan atas UU Perlindungan Anak sebagai dasar perlindungan hak anak.',
            ],
            [
                'title' => 'Undang-Undang Penghapusan KDRT',
                'number' => 'UU No. 23 Tahun 2004',
                'year' => 2004,
                'category' => 'Undang-Undang',
                'summary' => 'Landasan hukum pencegahan dan penanganan kekerasan dalam rumah tangga.',
            ],
            [
                'title' => 'Peraturan Pelayanan Aduan Masyarakat',
                'number' => 'Perkap No. 9 Tahun 2022',
                'year' => 2022,
                'category' => 'Peraturan',
                'summary' => 'Pedoman teknis pelayanan aduan masyarakat berbasis akuntabilitas.',
            ],
            [
                'title' => 'SOP Penanganan Kasus PPA/PPO',
                'number' => 'SOP-01/PPA/2025',
                'year' => 2025,
                'category' => 'SOP',
                'summary' => 'Standar operasional penanganan kasus perempuan dan anak.',
            ],
            [
                'title' => 'Pedoman Pendampingan Korban',
                'number' => 'Pedoman Internal 2025',
                'year' => 2025,
                'category' => 'Pedoman',
                'summary' => 'Panduan pendampingan korban berbasis kebutuhan pemulihan.',
            ],
            [
                'title' => 'Panduan Pelaporan Digital Masyarakat',
                'number' => 'Panduan Layanan 2026',
                'year' => 2026,
                'category' => 'Panduan',
                'summary' => 'Panduan penggunaan kanal pelaporan online yang aman dan terstruktur.',
            ],
        ];

        foreach ($documents as $index => $document) {
            $slug = Str::slug($document['title']);
            $filePath = PlaceholderMedia::ensurePdf('documents/'.$slug.'.pdf', $document['title']);

            Document::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $document['title'],
                    'number' => $document['number'],
                    'year' => $document['year'],
                    'category' => $document['category'],
                    'summary' => $document['summary'],
                    'file_path' => $filePath,
                    'is_published' => true,
                    'published_at' => now()->subDays(($index + 1) * 5),
                ]
            );
        }
    }
}
