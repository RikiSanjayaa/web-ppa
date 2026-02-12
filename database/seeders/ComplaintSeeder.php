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

        // Daftar titik jangkar (anchor points) di pemukiman padat penduduk NTB
        // Format: Nama Kecamatan/Kelurahan => [lat, lng, radius_km]
        // Radius 0.01 derajat ~ 1.11 km
        $anchorPoints = [
            // KOTA MATARAM
            'Mataram (Pusat)' => ['lat' => -8.5838, 'lng' => 116.1105, 'radius' => 0.015],
            'Ampenan' => ['lat' => -8.5779, 'lng' => 116.0853, 'radius' => 0.01],
            'Cakranegara' => ['lat' => -8.5956, 'lng' => 116.1450, 'radius' => 0.012],
            'Sekarbela' => ['lat' => -8.6022, 'lng' => 116.1005, 'radius' => 0.01],
            'Selagalas' => ['lat' => -8.5750, 'lng' => 116.1600, 'radius' => 0.01],

            // LOMBOK BARAT
            'Gerung (Pusat Pemkab)' => ['lat' => -8.6788, 'lng' => 116.1245, 'radius' => 0.015],
            'Narmada' => ['lat' => -8.5630, 'lng' => 116.2230, 'radius' => 0.012],
            'Senggigi (Kawasan Wisata)' => ['lat' => -8.4520, 'lng' => 116.0420, 'radius' => 0.01],
            'Lembar (Pelabuhan)' => ['lat' => -8.7290, 'lng' => 116.0770, 'radius' => 0.01],
            'Gunung Sari' => ['lat' => -8.5300, 'lng' => 116.1100, 'radius' => 0.01],

            // LOMBOK TENGAH
            'Praya (Pusat Kota)' => ['lat' => -8.7105, 'lng' => 116.2730, 'radius' => 0.02],
            'Kopang' => ['lat' => -8.6360, 'lng' => 116.3550, 'radius' => 0.01],
            'Pujut (Mandalika)' => ['lat' => -8.8950, 'lng' => 116.2900, 'radius' => 0.02],
            'Janapria' => ['lat' => -8.6800, 'lng' => 116.3900, 'radius' => 0.01],
            'Pringgarata' => ['lat' => -8.5900, 'lng' => 116.2300, 'radius' => 0.01],

            // LOMBOK TIMUR
            'Selong (Pusat Kota)' => ['lat' => -8.6505, 'lng' => 116.5350, 'radius' => 0.015],
            'Masbagik' => ['lat' => -8.6200, 'lng' => 116.4800, 'radius' => 0.012],
            'Aikmel' => ['lat' => -8.5800, 'lng' => 116.5500, 'radius' => 0.01],
            'Labuhan Haji' => ['lat' => -8.6900, 'lng' => 116.5800, 'radius' => 0.01],
            'Pringgabaya' => ['lat' => -8.5200, 'lng' => 116.6300, 'radius' => 0.012],

            // LOMBOK UTARA
            'Tanjung (Pusat Pemerintahan)' => ['lat' => -8.3530, 'lng' => 116.1550, 'radius' => 0.01],
            'Pemenang (Bangsal)' => ['lat' => -8.4100, 'lng' => 116.0900, 'radius' => 0.008],
            'Gangga' => ['lat' => -8.3100, 'lng' => 116.1900, 'radius' => 0.01],

            // SUMBAWA
            'Sumbawa Besar (Pusat Kota)' => ['lat' => -8.5040, 'lng' => 117.4300, 'radius' => 0.02],
            'Alas' => ['lat' => -8.5400, 'lng' => 116.9600, 'radius' => 0.01],
            'Plampang' => ['lat' => -8.7600, 'lng' => 117.7800, 'radius' => 0.01],

            // SUMBAWA BARAT
            'Taliwang (Pusat Kota)' => ['lat' => -8.7450, 'lng' => 116.8550, 'radius' => 0.015],
            'Maluk (Kawasan Tambang)' => ['lat' => -8.8900, 'lng' => 116.8300, 'radius' => 0.01],
            'Seteluk' => ['lat' => -8.6300, 'lng' => 116.8600, 'radius' => 0.01],

            // DOMPU
            'Dompu (Pusat Kota)' => ['lat' => -8.5360, 'lng' => 118.4630, 'radius' => 0.015],
            'Woja' => ['lat' => -8.5600, 'lng' => 118.4400, 'radius' => 0.01],
            'Kilo' => ['lat' => -8.3300, 'lng' => 118.3900, 'radius' => 0.01],

            // BIMA (KABUPATEN)
            'Woha (Ibukota Kabupaten)' => ['lat' => -8.6200, 'lng' => 118.6800, 'radius' => 0.015],
            'Bolo' => ['lat' => -8.5100, 'lng' => 118.6300, 'radius' => 0.01],
            'Sape (Pelabuhan)' => ['lat' => -8.5700, 'lng' => 119.0100, 'radius' => 0.012],

            // KOTA BIMA
            'Raba (Pusat Kota)' => ['lat' => -8.4550, 'lng' => 118.7500, 'radius' => 0.015],
            'Mpunda' => ['lat' => -8.4700, 'lng' => 118.7200, 'radius' => 0.01],
            'Asakota' => ['lat' => -8.4300, 'lng' => 118.7300, 'radius' => 0.01],
        ];

        for ($i = 0; $i < 100; $i++) {
            // Tanggal acak dalam 30 hari terakhir, lebih banyak di minggu terkini
            $daysAgo = rand(0, 30);
            if (rand(0, 10) > 7) {
                $daysAgo = rand(0, 7);
            }

            $baseTime = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Pilih lokasi anchor secara acak
            $anchorName = array_rand($anchorPoints);
            $anchorData = $anchorPoints[$anchorName];

            // Koordinat acak di sekitar anchor point dengan radius kecil (presisi tinggi)
            // Menggunakan distribusi normal mendekati pusat anchor agar lebih realistis
            $latOffset = ($faker->randomFloat(6, -1, 1) + $faker->randomFloat(6, -1, 1)) / 2 * $anchorData['radius'];
            $lngOffset = ($faker->randomFloat(6, -1, 1) + $faker->randomFloat(6, -1, 1)) / 2 * $anchorData['radius'];

            $lat = $anchorData['lat'] + $latOffset;
            $lng = $anchorData['lng'] + $lngOffset;

            // Alamat teks termasuk nama wilayah untuk pengujian hybrid
            $streetAddress = $faker->streetAddress.', '.$anchorName;

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
