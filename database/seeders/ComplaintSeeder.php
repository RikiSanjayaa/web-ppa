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

        for ($i = 1; $i <= 20; $i++) {
            $status = $statusPattern[($i - 1) % count($statusPattern)];
            $baseTime = now()->subDays($i * 2)->subHours($i);

            $complaint = Complaint::query()->create([
                'nama_lengkap' => 'Pelapor Demo '.$i,
                'nik' => $i % 2 === 0 ? sprintf('3174%012d', $i) : null,
                'alamat' => 'Jl. Contoh No. '.$i.', Jakarta',
                'no_hp' => '0812'.str_pad((string) (100000 + $i), 6, '0', STR_PAD_LEFT),
                'email' => $i % 3 === 0 ? 'pelapor'.$i.'@contoh.com' : null,
                'tempat_kejadian' => 'Lokasi Kejadian '.$i,
                'waktu_kejadian' => $baseTime->copy()->subDays(1),
                'kronologis_singkat' => 'Kronologis demo aduan ke-'.$i.'. Pelapor menjelaskan kejadian secara singkat untuk kebutuhan seed data.',
                'korban' => $i % 2 === 0 ? 'Korban '.$i : null,
                'terlapor' => $i % 4 === 0 ? 'Terlapor '.$i : null,
                'saksi_saksi' => $i % 5 === 0 ? 'Saksi A, Saksi B' : null,
                'status' => $status,
                'channel' => 'web',
                'wa_redirected_at' => $baseTime,
                'ip_address' => '127.0.0.'.(($i % 200) + 1),
                'user_agent' => 'SeederBot/1.0',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            ComplaintStatusHistory::query()->create([
                'complaint_id' => $complaint->id,
                'changed_by' => null,
                'from_status' => null,
                'to_status' => Complaint::STATUS_BARU,
                'note' => 'Aduan dibuat dari form web.',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            if (in_array($status, [Complaint::STATUS_DIPROSES, Complaint::STATUS_SELESAI], true)) {
                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_BARU,
                    'to_status' => Complaint::STATUS_DIPROSES,
                    'note' => 'Aduan diverifikasi dan masuk tahap proses.',
                    'created_at' => $baseTime->copy()->addHours(6),
                    'updated_at' => $baseTime->copy()->addHours(6),
                ]);
            }

            if ($status === Complaint::STATUS_SELESAI) {
                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_DIPROSES,
                    'to_status' => Complaint::STATUS_SELESAI,
                    'note' => 'Tindak lanjut selesai dan laporan ditutup.',
                    'created_at' => $baseTime->copy()->addHours(18),
                    'updated_at' => $baseTime->copy()->addHours(18),
                ]);
            }
        }
    }
}
