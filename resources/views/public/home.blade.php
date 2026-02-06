@extends('layouts.public')

@section('title', 'Home | '.$settings['site_name'])
@section('meta_description', 'Layanan resmi perlindungan perempuan dan anak: hotline WA, aduan online, informasi hukum, dan galeri kegiatan.')

@section('content')
    <section class="rounded-3xl bg-gradient-to-r from-navy-700 via-navy-600 to-teal-600 px-6 py-12 text-white lg:px-12">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
            <div data-aos="fade-up">
                <p class="mb-2 inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide">Layanan Resmi PPA/PPO</p>
                <h1 class="font-heading text-3xl font-bold leading-tight lg:text-5xl">{{ $settings['hero_title'] }}</h1>
                <p class="mt-4 text-base text-slate-100 lg:text-lg">{{ $settings['hero_subtitle'] }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('layanan-masyarakat') }}#form-aduan" class="btn border-0 bg-coral-500 text-white hover:bg-coral-600">Buat Aduan</a>
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['hotline_wa_number']) }}" target="_blank" rel="noopener" class="btn border-white/40 bg-white/10 text-white hover:bg-white/20">Hotline WhatsApp</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3" data-aos="fade-left">
                <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-wide text-slate-200">Total Aduan</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($stats['total_aduan']) }}</p>
                </div>
                <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-wide text-slate-200">Aduan Selesai</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($stats['aduan_selesai']) }}</p>
                </div>
                <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-wide text-slate-200">Dokumen Hukum</p>
                    <p class="mt-2 text-3xl font-bold">{{ number_format($stats['total_dokumen']) }}</p>
                </div>
                <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-wide text-slate-200">Layanan</p>
                    <p class="mt-2 text-lg font-semibold">Cepat & Responsif</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-10 grid gap-6 lg:grid-cols-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-6" data-aos="fade-up">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Aduan Masyarakat</h2>
            <p class="mt-2 text-sm text-slate-600">Sampaikan aduan dengan aman. Data Anda masuk sistem dan diarahkan ke hotline WhatsApp.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6" data-aos="fade-up" data-aos-delay="80">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Pendampingan Kasus</h2>
            <p class="mt-2 text-sm text-slate-600">Layanan koordinasi, asesmen, dan tindak lanjut perlindungan perempuan dan anak.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6" data-aos="fade-up" data-aos-delay="160">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Edukasi Hukum</h2>
            <p class="mt-2 text-sm text-slate-600">Akses dokumen UU/peraturan yang dapat diunduh untuk edukasi masyarakat.</p>
        </article>
    </section>

    <section class="mt-12" data-aos="fade-up">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-heading text-2xl font-semibold text-navy-700">Berita & Event Terbaru</h2>
            <a href="{{ route('informasi.index') }}" class="text-sm font-semibold text-coral-600 hover:text-coral-700">Lihat semua</a>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @forelse ($newsPosts as $post)
                <article class="rounded-2xl border border-slate-200 bg-white p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-coral-600">{{ $post->type }}</p>
                    <h3 class="mt-2 font-heading text-lg font-semibold text-slate-800">{{ $post->title }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}</p>
                    <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                        <span>{{ optional($post->published_at)->translatedFormat('d M Y') }}</span>
                        <a href="{{ route('informasi.show', $post->slug) }}" class="font-semibold text-navy-700">Detail</a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 md:col-span-3">
                    Belum ada berita atau event yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-12" data-aos="fade-up">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-heading text-2xl font-semibold text-navy-700">Atasan Terkait</h2>
            <a href="{{ route('organisasi') }}" class="text-sm font-semibold text-coral-600 hover:text-coral-700">Lihat organisasi</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($leaders as $leader)
                <article class="rounded-2xl border border-slate-200 bg-white p-4">
                    @if ($leader->photo_path)
                        <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}" class="h-44 w-full rounded-xl object-cover">
                    @else
                        <div class="flex h-44 items-center justify-center rounded-xl bg-slate-100 text-sm text-slate-400">Foto belum tersedia</div>
                    @endif
                    <h3 class="mt-3 font-heading text-lg font-semibold text-slate-800">{{ $leader->name }}</h3>
                    <p class="text-sm text-coral-600">{{ $leader->position }}</p>
                    @if ($leader->bio)
                        <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $leader->bio }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                    Data atasan belum tersedia.
                </div>
            @endforelse
        </div>
    </section>
@endsection
