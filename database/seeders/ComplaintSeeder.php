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

        $faker = \Faker\Factory::create('id_ID');

        // Daftar kronologis kejadian yang realistis dalam Bahasa Indonesia
        $chronologies = [
            'Korban mengalami kekerasan fisik oleh suami setelah bertengkar masalah ekonomi keluarga. Kejadian berlangsung di ruang tamu rumah kami pada malam hari.',
            'Anak tetangga saya diduga mengalami pelecehan seksual oleh orang tidak dikenal di dekat sekolahnya saat pulang sekolah.',
            'Terjadi kasus penelantaran anak oleh orang tua kandung yang tidak memberikan nafkah dan tempat tinggal yang layak selama 3 bulan terakhir.',
            'Korban diancam akan disebarkan foto peribadinya jika tidak menuruti keinginan pelaku. Pelaku menghubungi korban lewat media sosial.',
            'Ada indikasi perdagangan orang dengan modus penawaran kerja ke luar negeri tanpa dokumen resmi.',
            'Suami melakukan pemukulan terhadap istri di depan anak-anak karena masalah sepele.',
            'Anak di bawah umur dipaksa bekerja sebagai pengemis di lampu merah oleh sindikat tertentu.',
            'Terjadi pertengkaran hebat yang berujung pada kekerasan verbal dan fisik dalam rumah tangga.',
            'Pelaku melakukan tindakan asusila terhadap korban di tempat umum saat kondisi sepi.',
            'Korban merasa diikuti dan diteror oleh mantan pacar yang tidak terima diputuskan.',
        ];

        // Daftar wilayah NTB beserta pusat koordinat dan radius
        $regions = [
            'Mataram' => ['lat' => -8.5833, 'lng' => 116.1167, 'radius' => 0.05],
            'Lombok Barat' => ['lat' => -8.6833, 'lng' => 116.1167, 'radius' => 0.1],
            'Lombok Tengah' => ['lat' => -8.7000, 'lng' => 116.2667, 'radius' => 0.1],
            'Lombok Timur' => ['lat' => -8.6500, 'lng' => 116.5333, 'radius' => 0.15],
            'Lombok Utara' => ['lat' => -8.3500, 'lng' => 116.1500, 'radius' => 0.1],
            'Sumbawa' => ['lat' => -8.5000, 'lng' => 117.4333, 'radius' => 0.2],
            'Sumbawa Barat' => ['lat' => -8.7500, 'lng' => 116.8500, 'radius' => 0.1],
            'Dompu' => ['lat' => -8.5333, 'lng' => 118.4667, 'radius' => 0.1],
            'Bima' => ['lat' => -8.6000, 'lng' => 118.7000, 'radius' => 0.15],
            'Kota Bima' => ['lat' => -8.4667, 'lng' => 118.7333, 'radius' => 0.05],
        ];

        for ($i = 0; $i < 100; $i++) {
            // Tanggal acak dalam 30 hari terakhir, lebih banyak di minggu terkini
            $daysAgo = rand(0, 30);
            if (rand(0, 10) > 7) {
                $daysAgo = rand(0, 7);
            }

            $baseTime = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Pilih wilayah secara acak
            $regionName = array_rand($regions);
            $regionData = $regions[$regionName];

            // Koordinat acak di sekitar pusat wilayah
            $lat = $regionData['lat'] + ($faker->randomFloat(6, -1, 1) * $regionData['radius']);
            $lng = $regionData['lng'] + ($faker->randomFloat(6, -1, 1) * $regionData['radius']);

            // Alamat teks termasuk nama wilayah untuk pengujian hybrid
            $streetAddress = $faker->streetAddress.', '.$regionName;

            // Status acak dengan bobot tertentu
            $statusRoll = rand(1, 100);
            if ($statusRoll <= 40) {
                $status = Complaint::STATUS_MASUK;
            } elseif ($statusRoll <= 75) {
                // Pilih salah satu status diproses secara acak
                $processingStatuses = [
                    Complaint::STATUS_DIPROSES_LP,
                    Complaint::STATUS_DIPROSES_LIDIK,
                    Complaint::STATUS_DIPROSES_SIDIK,
                ];
                $status = $processingStatuses[array_rand($processingStatuses)];
            } else {
                $status = Complaint::STATUS_TAHAP_1;
            }

            $complaint = Complaint::query()->create([
                'nama_lengkap' => $faker->name,
                'nik' => $faker->nik,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'email' => rand(0, 1) ? $faker->email : null,
                'tempat_kejadian' => $streetAddress,
                'latitude' => $lat,
                'longitude' => $lng,
                'waktu_kejadian' => $baseTime->copy()->subDays(rand(0, 5)),
                'kronologis_singkat' => $faker->randomElement($chronologies).' '.$faker->sentence,
                'korban' => $faker->name,
                'terlapor' => rand(0, 1) ? $faker->name : null,
                'saksi_saksi' => rand(0, 1) ? $faker->name.', '.$faker->name : null,
                'status' => $status,
                'channel' => rand(0, 10) > 8 ? 'dibuat oleh admin' : 'web',
                'wa_redirected_at' => rand(0, 1) ? $baseTime : null,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            // Buat riwayat status awal
            ComplaintStatusHistory::query()->create([
                'complaint_id' => $complaint->id,
                'changed_by' => null,
                'from_status' => null,
                'to_status' => Complaint::STATUS_MASUK,
                'note' => 'Aduan dibuat.',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ]);

            // Tambahkan riwayat untuk status lanjutan
            if ($status !== Complaint::STATUS_MASUK) {
                $processTime = $baseTime->copy()->addHours(rand(1, 24));
                // Pastikan waktu proses tidak melebihi sekarang
                if ($processTime->gt(now())) {
                    $processTime = now();
                }

                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_MASUK,
                    'to_status' => $status === Complaint::STATUS_TAHAP_1 ? Complaint::STATUS_DIPROSES_LIDIK : $status,
                    'note' => 'Aduan sedang diproses.',
                    'created_at' => $processTime,
                    'updated_at' => $processTime,
                ]);
            }

            if ($status === Complaint::STATUS_TAHAP_1) {
                $finishTime = $baseTime->copy()->addHours(rand(25, 120));
                // Pastikan waktu selesai tidak melebihi sekarang
                if ($finishTime->gt(now())) {
                    $finishTime = now();
                }

                ComplaintStatusHistory::query()->create([
                    'complaint_id' => $complaint->id,
                    'changed_by' => $defaultChangerId,
                    'from_status' => Complaint::STATUS_DIPROSES_LIDIK,
                    'to_status' => Complaint::STATUS_TAHAP_1,
                    'note' => 'Kasus telah diselesaikan (Tahap 1).',
                    'created_at' => $finishTime,
                    'updated_at' => $finishTime,
                ]);
            }
        }
    }
}
