<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ComplaintStatusHistory::query()->delete();
        Complaint::query()->delete();

        $adminIds = User::query()->where('is_admin', true)->pluck('id')->all();
        $defaultChangerId = $adminIds[0] ?? null;

        $statusPattern = [
            Complaint::STATUS_BARU,
            Complaint::STATUS_DIPROSES,
            Complaint::STATUS_SELESAI,
            Complaint::STATUS_DIPROSES,
            Complaint::STATUS_SELESAI,
            Complaint::STATUS_BARU,
            Complaint::STATUS_SELESAI,
            Complaint::STATUS_DIPROSES,
            Complaint::STATUS_BARU,
            Complaint::STATUS_SELESAI,
        ];

        $faker = \Faker\Factory::create('id_ID');

        // Generate 100 fake complaints
        for ($i = 0; $i < 100; $i++) {
            // Random date within last 30 days, weighted towards recent days
            $daysAgo = rand(0, 30);
            if (rand(0, 10) > 7) {
                $daysAgo = rand(0, 7); // 30% chance of being in the last week
            }

            $baseTime = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Random status with some weight
            $statusRoll = rand(1, 100);
            if ($statusRoll <= 40) {
                $status = Complaint::STATUS_BARU;
            } elseif ($statusRoll <= 75) {
                $status = Complaint::STATUS_DIPROSES;
            } else {
                $status = Complaint::STATUS_SELESAI;
            }

            $complaint = Complaint::query()->create([
                'nama_lengkap' => $faker->name,
                'nik' => $faker->nik,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'email' => rand(0, 1) ? $faker->email : null,
                'tempat_kejadian' => $faker->streetAddress,
                'waktu_kejadian' => $baseTime->copy()->subDays(rand(0, 5)), // Kejadian happened before report
                'kronologis_singkat' => $faker->paragraph,
                'korban' => $faker->name,
                'terlapor' => rand(0, 1) ? $faker->name : null,
                'saksi_saksi' => rand(0, 1) ? $faker->name . ', ' . $faker->name : null,
                'status' => $status,
                'channel' => rand(0, 10) > 8 ? 'dibuat oleh admin' : 'web', // Mostly web
                'wa_redirected_at' => rand(0, 1) ? $baseTime : null,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            // Always create initial history
            ComplaintStatusHistory::query()->create([
                'complaint_id' => $complaint->id,
                'changed_by' => null,
                'from_status' => null,
                'to_status' => Complaint::STATUS_BARU,
                'note' => 'Aduan dibuat.',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            // Add history for subsequent statuses
            if ($status === Complaint::STATUS_DIPROSES || $status === Complaint::STATUS_SELESAI) {
                $processTime = $baseTime->copy()->addHours(rand(1, 24));
                // Ensure process time doesn't exceed now
                if ($processTime->gt(now()))
                    $processTime = now();

                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_BARU,
                    'to_status' => Complaint::STATUS_DIPROSES,
                    'note' => 'Aduan sedang diproses.',
                    'created_at' => $processTime,
                    'updated_at' => $processTime,
                ]);
            }

            if ($status === Complaint::STATUS_SELESAI) {
                $finishTime = $baseTime->copy()->addHours(rand(25, 120)); // 1-5 days later
                // Ensure finish time doesn't exceed now
                if ($finishTime->gt(now()))
                    $finishTime = now();

                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_DIPROSES,
                    'to_status' => Complaint::STATUS_SELESAI,
                    'note' => 'Kasus telah diselesaikan.',
                    'created_at' => $finishTime,
                    'updated_at' => $finishTime,
                ]);
            }
        }
    }
}
