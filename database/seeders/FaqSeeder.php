<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
  public function run(): void
  {
    $faqs = [
      [
        'question' => 'Apa saja yang bisa dilaporkan ke PPA/PPO?',
        'answer' => 'Anda dapat melaporkan kasus kekerasan dalam rumah tangga (KDRT), pelecehan seksual, eksploitasi anak, perdagangan orang, penelantaran, dan bentuk kekerasan lainnya terhadap perempuan dan anak.',
        'order' => 1,
      ],
      [
        'question' => 'Apakah identitas pelapor dirahasiakan?',
        'answer' => 'Ya, identitas pelapor dijamin kerahasiaannya. Data Anda hanya digunakan untuk keperluan penanganan kasus dan tidak akan disebarkan kepada pihak manapun tanpa persetujuan Anda.',
        'order' => 2,
      ],
      [
        'question' => 'Bagaimana cara melaporkan via WhatsApp?',
        'answer' => 'Klik tombol "Laporkan via WhatsApp" di halaman ini, Anda akan diarahkan ke chat WhatsApp dengan petugas. Sampaikan kronologi kejadian dan lokasi Anda. Petugas akan merespons dalam waktu 1x24 jam.',
        'order' => 3,
      ],
      [
        'question' => 'Apakah layanan ini gratis?',
        'answer' => 'Ya, seluruh layanan konsultasi, pendampingan, dan perlindungan dari PPA/PPO bersifat gratis tanpa dipungut biaya apapun.',
        'order' => 4,
      ],
      [
        'question' => 'Berapa lama proses penanganan laporan?',
        'answer' => 'Laporan darurat akan ditindaklanjuti segera (maksimal 1x24 jam). Untuk kasus non-darurat, proses asesmen dan tindak lanjut dilakukan dalam 3-7 hari kerja tergantung kompleksitas kasus.',
        'order' => 5,
      ],
      [
        'question' => 'Siapa yang bisa melapor?',
        'answer' => 'Siapa saja dapat melapor: korban langsung, keluarga korban, saksi, tetangga, atau pihak yang mengetahui adanya tindak kekerasan terhadap perempuan dan anak.',
        'order' => 6,
      ],
      [
        'question' => 'Apakah saya perlu bukti untuk melapor?',
        'answer' => 'Tidak wajib. Anda tetap bisa melapor meskipun belum memiliki bukti fisik. Petugas akan membantu proses pengumpulan bukti dan dokumentasi selanjutnya.',
        'order' => 7,
      ],
      [
        'question' => 'Bagaimana jika saya dalam kondisi darurat?',
        'answer' => 'Segera hubungi hotline WhatsApp atau telepon yang tersedia di halaman ini. Jika situasi mengancam jiwa, hubungi 110 (Polisi) atau langsung datang ke kantor Polres/Polsek terdekat.',
        'order' => 8,
      ],
    ];

    foreach ($faqs as $faq) {
      Faq::create($faq);
    }
  }
}
