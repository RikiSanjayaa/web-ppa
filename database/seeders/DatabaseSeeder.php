<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiteSettingSeeder::class,
            LeaderSeeder::class,
            NewsPostSeeder::class,
            DocumentSeeder::class,

            TestimonialSeeder::class,
            FaqSeeder::class,
            ComplaintSeeder::class,
            ConsultationSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
