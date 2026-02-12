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

        $problems = [
            "Saya ingin berkonsultasi mengenai hak asuh anak setelah perceraian.",
            "Bagaimana prosedur melaporkan kdrt yang dialami saudara saya?",
            "Tetangga saya sering memukul anaknya, apakah saya bisa melaporkannya?",
            "Saya merasa diikuti orang tidak dikenal, apa yang harus saya lakukan?",
            "Anak saya menjadi korban bullying di sekolah, mohon sarannya.",
            "Apakah ada perlindungan hukum untuk saksi kasus kekerasan seksual?",
            "Saya ingin tahu cara mendapatkan bantuan hukum gratis untuk korban KDRT.",
            "Suami saya tidak memberi nafkah selama setahun, apakah ini termasuk penelantaran?",
            "Adakah rumah aman untuk korban kekerasan di daerah Lombok Barat?",
            "Saya mendapat ancaman penyebaran video pribadi, saya takut melapor ke polisi."
        ];

        $recommendations = [
            "Disarankan untuk segera melapor ke UPTD PPA terdekat untuk pendampingan.",
            "Silakan kumpulkan bukti-bukti awal seperti foto atau rekam medis visum.",
            "Kami sarankan untuk berkonsultasi dengan psikolog klinis terlebih dahulu untuk pemulihan trauma.",
            "Sebaiknya jangan bepergian sendirian dan catat nomor darurat kepolisian.",
            "Perlu dilakukan mediasi dengan pihak sekolah dan orang tua pelaku.",
            "Lembaga Bantuan Hukum (LBH) setempat dapat memberikan advokasi pro bono.",
            "Anda berhak mendapatkan perlindungan dari LPSK jika merasa terancam.",
            "Simpan semua bukti percakapan atau ancaman sebagai barang bukti digital."
        ];

        // Buat 50 data konsultasi dummy
        for ($i = 0; $i < 50; $i++) {
            // Pilih wilayah secara acak
            $regionName = array_rand($regions);
            $regionData = $regions[$regionName];
            
            // Koordinat acak di sekitar pusat wilayah
            $lat = $regionData['lat'] + ($faker->randomFloat(6, -1, 1) * $regionData['radius']);
            $lng = $regionData['lng'] + ($faker->randomFloat(6, -1, 1) * $regionData['radius']);

            Consultation::create([
                'nama_klien' => $faker->name,
                'permasalahan' => $faker->randomElement($problems) . " " . $faker->sentence,
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
