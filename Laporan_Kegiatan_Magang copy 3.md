# Laporan Kegiatan Magang - E-Cuti Polda & Monitoring PPA

## Minggu 1: Perancangan & Pengembangan Inti

*   **Jumat, 6 Februari 2026**
    Melaksanakan rapat inisiasi proyek, analisis kebutuhan sistem aplikasi laporan masyarakat, dan Monitoring PPA Polda NTB. Serta melakukan perancangan *starter project* untuk membedakan struktur *landing page* dan *admin panel*.

## Minggu 2: Implementasi & Stabilisasi Sistem

*   **Senin, 9 Februari 2026**
    Mengembangkan antarmuka *dashboard* layanan publik dengan penambahan elemen visual resmi, dan merancang modul pencatatan kedaruratan (*log hotline access*) untuk rekam data masyarakat.

*   **Rabu, 11 Februari 2026**
    Menyelesaikan pembuatan kerangka awal basis data (database) fungsional, pembuatan halaman manajemen struktur organisasi pejabat/pimpinan, menu pengaturan FAQ, hingga persiapan integrasi dokumen layanan.

*   **Jumat, 13 Februari 2026**
    Memfinalisasi perancangan alur form pengaduan interaktif dan pengaturan resolusi responsif layar.

## Minggu 3: Optimalisasi & Keamanan Sistem

*   **Senin, 16 Februari 2026**
    Mengimplementasikan arsitektur sistem manajemen pengguna (*User Management*) tingkat lanjut berbasis *Role-Based Access Control* (Spatie), untuk mendistribusikan kewenangan hak akses antar admin dan pimpinan.

*   **Selasa, 17 Februari 2026**
    Menerapkan lapisan protokol keamanan ganda berupa pengiriman sandi darurat *Two-Factor Authentication (OTP)* berjangka waktu guna mencegah adanya intrusi ancaman siber terhadap sistem pangkalan.

*   **Rabu, 18 Februari 2026**
    Mengembangkan sinkronisasi sistem fitur *import* cerdas pada modul berita. Mengotomatisasi tarikan berita langsung dari Instagram untuk memangkas duplikasi entri publikasi oleh pelaksana.

*   **Kamis, 19 Februari 2026**
    Menyiapkan infrastruktur orkestrasi aplikasi menggunakan skenario isolasi peladen **Docker**. Memisahkan variabel *environment*, mempersiapkan *mount storage*, hingga merancang alur sinkronisasi Nginx guna memuluskan tahap persiapan migrasi server.

*   **Jumat, 20 Februari 2026**
    Melakukan optimalisasi kapasitas *upload* ukuran fail hingga skala 20 Megabyte dan menyempurnakan struktur navigasi kata (*SEO Tuning*) bagi peningkatan akurasi indeks Google.

## Minggu 4: Peluncuran (Launch) & Pemeliharaan Pasca-Rilis

*   **Senin, 23 Februari 2026**
    Melakukan eksekusi perilisan sistem dan **Deployment Server Aplikasi** secara menyeluruh ke dalam infrastruktur *production server dockerized*. Disambung dengan melakukan kegiatan pelatihan teknis pengoperasian aplikasi kepada staf kepolisian yang dilanjutkan pada serangkaian skenario pengisian data secara riil guna memantau (*monitoring*) jalannya rekapitulasi data kasus PPA/PPO secara *real-time*.
