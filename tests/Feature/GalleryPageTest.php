<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_page_loads_with_sqlite_year_query(): void
    {
        GalleryItem::query()->create([
            'title' => 'Kegiatan PPA',
            'category' => 'Sosialisasi',
            'event_date' => now()->toDateString(),
            'image_path' => 'gallery/sample.jpg',
            'is_published' => true,
            'display_order' => 1,
        ]);

        $response = $this->get(route('galeri.index'));

        $response->assertOk();
        $response->assertSee('Galeri Kegiatan');
    }
}
