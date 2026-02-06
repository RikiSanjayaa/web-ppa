<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Database\Seeders\Support\PlaceholderMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GalleryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Sosialisasi',
            'Pendampingan',
            'Kegiatan Internal',
            'Kemitraan',
        ];

        for ($i = 1; $i <= 16; $i++) {
            $title = 'Galeri Kegiatan PPA/PPO #'.$i;
            $slug = Str::slug($title);
            $category = $categories[($i - 1) % count($categories)];
            $eventDate = now()->subDays($i * 9)->toDateString();
            $imagePath = PlaceholderMedia::ensureImage('gallery/gallery-'.$i.'.jpg');

            GalleryItem::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'category' => $category,
                    'event_date' => $eventDate,
                    'image_path' => $imagePath,
                    'caption' => 'Dokumentasi kegiatan layanan '.$category.' untuk penguatan perlindungan perempuan dan anak.',
                    'display_order' => $i,
                    'is_published' => true,
                ]
            );
        }
    }
}
