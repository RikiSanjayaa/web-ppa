<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * ProductionSeeder — khusus untuk deploy server pertama kali.
 *
 * Hanya mengisi data esensial yang HARUS ada agar website berfungsi:
 * - Roles (super_admin, admin)
 * - Site Settings (konfigurasi tampilan)
 * - Leaders (halaman organisasi)
 * - FAQ (halaman layanan masyarakat)
 * - Documents (halaman informasi/regulasi)
 *
 * TIDAK termasuk data dummy: User, NewsPost, Complaint, Consultation,
 * Testimonial, ActivityLog — semua itu diisi manual via panel admin.
 *
 * Cara pakai:
 *   php artisan db:seed --class=ProductionSeeder --force
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // Buat role super_admin & admin
            SiteSettingSeeder::class,  // Konfigurasi tampilan website
            LeaderSeeder::class,      // Data pimpinan (halaman organisasi)
            FaqSeeder::class,         // FAQ layanan masyarakat
            DocumentSeeder::class,    // Dokumen regulasi
        ]);
    }
}
