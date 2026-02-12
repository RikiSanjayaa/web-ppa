<?php

namespace Database\Seeders;

use App\Models\Consultation;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Consultation::query()->delete();

        $faker = \Faker\Factory::create('id_ID');

        // Daftar titik jangkar (anchor points) di pemukiman padat penduduk NTB
        // Format: Nama Kecamatan/Kelurahan => [lat, lng, radius_km]
        // Radius 0.01 derajat ~ 1.11 km
        $anchorPoints = [
            // KOTA MATARAM
            'Mataram (Pusat)' => ['lat' => -8.5838, 'lng' => 116.1105, 'radius' => 0.015],
            'Ampenan' => ['lat' => -8.5779, 'lng' => 116.0853, 'radius' => 0.01],
            'Cakranegara' => ['lat' => -8.5956, 'lng' => 116.1450, 'radius' => 0.012],

            // LOMBOK BARAT
            'Gerung (Pusat Pemkab)' => ['lat' => -8.6788, 'lng' => 116.1245, 'radius' => 0.015],
            'Narmada' => ['lat' => -8.5630, 'lng' => 116.2230, 'radius' => 0.012],
            'Senggigi' => ['lat' => -8.4520, 'lng' => 116.0420, 'radius' => 0.01],

            // LOMBOK TENGAH
            'Praya (Pusat Kota)' => ['lat' => -8.7105, 'lng' => 116.2730, 'radius' => 0.02],
            'Kopang' => ['lat' => -8.6360, 'lng' => 116.3550, 'radius' => 0.01],
            'Pujut (Mandalika)' => ['lat' => -8.8950, 'lng' => 116.2900, 'radius' => 0.02],

            // LOMBOK TIMUR
            'Selong' => ['lat' => -8.6505, 'lng' => 116.5350, 'radius' => 0.015],
            'Masbagik' => ['lat' => -8.6200, 'lng' => 116.4800, 'radius' => 0.012],
            'Aikmel' => ['lat' => -8.5800, 'lng' => 116.5500, 'radius' => 0.01],

            // LOMBOK UTARA
            'Tanjung' => ['lat' => -8.3530, 'lng' => 116.1550, 'radius' => 0.01],
            'Pemenang' => ['lat' => -8.4100, 'lng' => 116.0900, 'radius' => 0.008],

            // SUMBAWA
            'Sumbawa Besar' => ['lat' => -8.5040, 'lng' => 117.4300, 'radius' => 0.02],
            'Alas' => ['lat' => -8.5400, 'lng' => 116.9600, 'radius' => 0.01],

            // SUMBAWA BARAT
            'Taliwang' => ['lat' => -8.7450, 'lng' => 116.8550, 'radius' => 0.015],

            // DOMPU
            'Dompu' => ['lat' => -8.5360, 'lng' => 118.4630, 'radius' => 0.015],

            // BIMA
            'Woha' => ['lat' => -8.6200, 'lng' => 118.6800, 'radius' => 0.015],
            'Raba (Kota Bima)' => ['lat' => -8.4550, 'lng' => 118.7500, 'radius' => 0.015],
        ];

        $problems = [
            'Saya ingin berkonsultasi mengenai hak asuh anak setelah perceraian.',
            'Bagaimana prosedur melaporkan kdrt yang dialami saudara saya?',
            'Tetangga saya sering memukul anaknya, apakah saya bisa melaporkannya?',
            'Saya merasa diikuti orang tidak dikenal, apa yang harus saya lakukan?',
            'Anak saya menjadi korban bullying di sekolah, mohon sarannya.',
            'Apakah ada perlindungan hukum untuk saksi kasus kekerasan seksual?',
            'Saya ingin tahu cara mendapatkan bantuan hukum gratis untuk korban KDRT.',
            'Suami saya tidak memberi nafkah selama setahun, apakah ini termasuk penelantaran?',
            'Adakah rumah aman untuk korban kekerasan di daerah Lombok Barat?',
            'Saya mendapat ancaman penyebaran video pribadi, saya takut melapor ke polisi.',
        ];

        $recommendations = [
            'Disarankan untuk segera melapor ke UPTD PPA terdekat untuk pendampingan.',
            'Silakan kumpulkan bukti-bukti awal seperti foto atau rekam medis visum.',
            'Kami sarankan untuk berkonsultasi dengan psikolog klinis terlebih dahulu untuk pemulihan trauma.',
            'Sebaiknya jangan bepergian sendirian dan catat nomor darurat kepolisian.',
            'Perlu dilakukan mediasi dengan pihak sekolah dan orang tua pelaku.',
            'Lembaga Bantuan Hukum (LBH) setempat dapat memberikan advokasi pro bono.',
            'Anda berhak mendapatkan perlindungan dari LPSK jika merasa terancam.',
            'Simpan semua bukti percakapan atau ancaman sebagai barang bukti digital.',
        ];

        // Buat 50 data konsultasi dummy
        for ($i = 0; $i < 50; $i++) {
            // Pilih lokasi anchor secara acak
            $anchorName = array_rand($anchorPoints);
            $anchorData = $anchorPoints[$anchorName];

            // Koordinat acak di sekitar anchor point dengan radius kecil (presisi tinggi)
            $latOffset = ($faker->randomFloat(6, -1, 1) + $faker->randomFloat(6, -1, 1)) / 2 * $anchorData['radius'];
            $lngOffset = ($faker->randomFloat(6, -1, 1) + $faker->randomFloat(6, -1, 1)) / 2 * $anchorData['radius'];

            $lat = $anchorData['lat'] + $latOffset;
            $lng = $anchorData['lng'] + $lngOffset;

            Consultation::create([
                'nama_klien' => $faker->name,
                'permasalahan' => $faker->randomElement($problems).' '.$faker->sentence,
                'rekomendasi' => rand(0, 1) ? $faker->randomElement($recommendations) : null,
                'latitude' => $lat,
                'longitude' => $lng,
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
            ]);
        }
    }
}
