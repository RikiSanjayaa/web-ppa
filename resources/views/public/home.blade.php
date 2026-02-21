@extends('layouts.public')

@section('title', 'Home | '.$settings['site_name'])
@section('meta_description', 'Layanan resmi perlindungan perempuan dan anak: hotline WA, aduan online, informasi hukum, dan galeri kegiatan.')

@section('content')
    {{-- Hero Section --}}
    <x-hero
        :title="$settings['hero_title']"
        :subtitle="$settings['hero_subtitle']"
        cta-text="Buat Laporan Pengaduan"
        :cta-url="route('layanan-masyarakat') . '#form-aduan'"
        secondary-cta-text="Konsultasi"
        secondary-cta-url="#"
        :secondary-cta-action="'showConsultationModal = true'"
        :stats="[
            'Total Aduan' => $stats['total_aduan'],
            'Aduan Selesai' => $stats['aduan_selesai'],
            'Dokumen Hukum' => $stats['total_dokumen'],
            'Layanan' => 'Cepat & Responsif',
        ]"
    />

    {{-- What Can Be Reported --}}
    <section class="mt-12">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Jenis Pelanggaran yang Dapat Dilaporkan</h2>
        <div class="mt-2 text-slate-600 space-y-2">
            <p>Berbagai kasus yang menyasar masyarakat rentan dapat langsung dilaporkan ke <strong>Ditreskrimum Polda NTB (Subdit IV Remaja, Anak, dan Wanita)</strong>. Kami menyediakan layanan <strong>Lapor Polisi Online</strong> yang cepat, rahasia, dan gratis.</p>
            <p>Jangan takut bicara! Segera lapor jika Anda atau kerabat terdekat menjadi korban <strong>KDRT (suami pukul istri/anak), pelecehan seksual, pencabulan, dugaan pemerkosaan, kekerasan pada anak balita, penipuan tenaga kerja (TKI ilegal/prostitusi), hingga ancaman siber (revenge porn)</strong> yang masuk dalam ranah Tinda Pidana.</p>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-report-card
                icon="home"
                title="KDRT (Kekerasan Dalam Rumah Tangga)"
                description="Suami/istri melakukan kekerasan fisik (memukul, melukai), pengancaman, kekerasan verbal yang parah, hingga penolakan memberikan nafkah dasar."
            />
            <x-report-card
                icon="heart"
                title="Pelecehan Seksual & Pencabulan"
                description="Tindakan asusila, pencabulan di bawah umur, percobaan pemerkosaan, pelecehan sentuhan fisik, atau eksploitasi seksual."
            />
            <x-report-card
                icon="users"
                title="Perdagangan Orang (TPPO)"
                description="Modus penipuan kerja ke luar negeri (TKI Ilegal), jebakan prostitusi, eksploitasi buruh anak, penyekapan, atau penjeratan hutang."
            />
            <x-report-card
                icon="hand"
                title="Kekerasan terhadap Anak"
                description="Penganiayaan, pemukulan anak kandung/tiri, penyiksaan fisik balita, penculikan, atau eksploitasi anak jalanan."
            />
            <x-report-card
                icon="briefcase"
                title="Penelantaran Keluarga"
                description="Mengusir istri/anak tanpa jalan keluar, menelantarkan bayi secara paksa, atau kepala keluarga lari dari tanggung jawab kehidupan dasar."
            />
            <x-report-card
                icon="exclamation"
                title="Ancaman & Perundungan Siber"
                description="Menerima teror ancaman pembunuhan, pemerasan foto pribadi (revenge-porn), penguntitan (stalking), atau kasus perundungan (bullying)."
            />
        </div>
    </section>

    {{-- How Reporting Works --}}
    <section class="mt-12" data-aos="fade-up">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Bagaimana Cara Melapor?</h2>
        <p class="mt-2 text-slate-600">Proses pelaporan yang mudah, cepat, dan kerahasiaan terjamin.</p>

        <div class="mt-6">
            <x-step-timeline :steps="[
                ['title' => 'Hubungi via WhatsApp', 'description' => 'Klik tombol WhatsApp di halaman ini. Anda akan terhubung langsung dengan petugas kami.'],
                ['title' => 'Ceritakan Kronologi', 'description' => 'Sampaikan apa yang terjadi, kapan, dan di mana. Tidak perlu bukti lengkap, kami akan membantu.'],
                ['title' => 'Asesmen & Tindak Lanjut', 'description' => 'Tim kami akan melakukan asesmen dan menentukan langkah perlindungan yang diperlukan.'],
                ['title' => 'Pendampingan', 'description' => 'Anda akan didampingi hingga kasus selesai. Identitas Anda dijamin kerahasiaannya.'],
            ]" />
        </div>
    </section>

    {{-- About the Unit --}}
    <section class="mt-12 rounded-2xl bg-gradient-to-br from-slate-50 to-white border border-slate-200 p-6 lg:p-8" data-aos="fade-up">
        <div class="flex items-start gap-4">
            <div class="hidden shrink-0 lg:block">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-600 to-teal-600">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div>
                <h2 class="font-heading text-2xl font-semibold text-navy-700">Tentang ditres PPA & PPO</h2>
                <p class="mt-3 leading-relaxed text-slate-600">{{ $settings['organization_profile'] }}</p>

                <div class="mt-4 rounded-xl bg-teal-50 border border-teal-100 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-teal-800">Kerahasiaan Terjamin</p>
                            <p class="mt-1 text-sm text-teal-700">{{ $settings['about_confidentiality'] ?? 'Identitas pelapor dijamin kerahasiaannya. Data hanya digunakan untuk keperluan penanganan kasus.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Recent Events --}}
    <section class="mt-12" data-aos="fade-up">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-heading text-2xl font-semibold text-navy-700">Berita & Event Terbaru</h2>
            <a href="{{ route('galeri.index') }}" class="text-sm font-semibold text-coral-600 hover:text-coral-700">Lihat semua</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($newsPosts->take(6) as $post)
                <x-event-card :post="$post" />
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 md:col-span-2 lg:col-span-3">
                    Belum ada berita atau event yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>


@endsection
