<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\GalleryItem;
use App\Models\Leader;
use App\Models\NewsPost;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityLog::query()->delete();

        $admin = User::query()->where('is_admin', true)->first();
        $adminId = $admin?->id;

        $entries = [
            [
                'action' => 'site_settings.updated',
                'subject' => null,
                'description' => 'Pengaturan situs diperbarui melalui dashboard admin.',
                'properties' => ['module' => 'settings'],
            ],
            [
                'action' => 'news_post.created',
                'subject' => NewsPost::query()->latest('id')->first(),
                'description' => 'Konten berita/event dibuat.',
                'properties' => ['module' => 'news-posts'],
            ],
            [
                'action' => 'document.updated',
                'subject' => Document::query()->latest('id')->first(),
                'description' => 'Dokumen informasi diperbarui.',
                'properties' => ['module' => 'documents'],
            ],
            [
                'action' => 'gallery_item.created',
                'subject' => GalleryItem::query()->latest('id')->first(),
                'description' => 'Item galeri ditambahkan.',
                'properties' => ['module' => 'gallery-items'],
            ],
            [
                'action' => 'leader.updated',
                'subject' => Leader::query()->latest('id')->first(),
                'description' => 'Data atasan diperbarui.',
                'properties' => ['module' => 'leaders'],
            ],
            [
                'action' => 'testimonial.created',
                'subject' => Testimonial::query()->latest('id')->first(),
                'description' => 'Testimoni baru dipublikasikan.',
                'properties' => ['module' => 'testimonials'],
            ],
            [
                'action' => 'complaint.status_updated',
                'subject' => Complaint::query()->where('status', Complaint::STATUS_DIPROSES)->latest('id')->first(),
                'description' => 'Status aduan diperbarui dari baru ke diproses.',
                'properties' => ['module' => 'complaints', 'to_status' => Complaint::STATUS_DIPROSES],
            ],
        ];

        foreach ($entries as $index => $entry) {
            $subject = $entry['subject'];
            $timestamp = now()->subHours(($index + 1) * 3);

            ActivityLog::query()->create([
                'user_id' => $adminId,
                'action' => $entry['action'],
                'subject_type' => $subject ? $subject::class : null,
                'subject_id' => $subject?->id,
                'description' => $entry['description'],
                'properties' => $entry['properties'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'SeederBot/1.0',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
