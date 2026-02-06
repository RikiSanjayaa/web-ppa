@extends('layouts.public')

@section('title', 'Galeri | '.$settings['site_name'])
@section('meta_description', 'Galeri foto kegiatan PPA/PPO berdasarkan kategori dan tahun.')

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8" data-aos="fade-up">
        <h1 class="font-heading text-3xl font-bold text-navy-700">Galeri Kegiatan</h1>
        <p class="mt-2 text-slate-600">Dokumentasi kegiatan dan aktivitas layanan PPA/PPO.</p>

        <form method="GET" action="{{ route('galeri.index') }}" class="mt-5 grid gap-3 md:grid-cols-4 md:items-end">
            <label class="form-control">
                <span class="mb-1 text-sm font-semibold text-slate-700">Kategori</span>
                <select name="category" class="select select-bordered">
                    <option value="">Semua</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="mb-1 text-sm font-semibold text-slate-700">Tahun</span>
                <select name="year" class="select select-bordered">
                    <option value="">Semua</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" @selected((string) $selectedYear === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
            <a href="{{ route('galeri.index') }}" class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>
        </form>
    </section>

    <section class="mt-8" data-aos="fade-up">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleryItems as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-3">
                    <a href="{{ Storage::url($item->image_path) }}" class="glightbox block" data-gallery="ppa-gallery" data-title="{{ $item->title }}">
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="h-52 w-full rounded-xl object-cover">
                    </a>
                    <h2 class="mt-3 font-heading text-lg font-semibold text-slate-800">{{ $item->title }}</h2>
                    <p class="text-sm text-slate-500">
                        {{ $item->category ?: 'Umum' }}
                        @if ($item->event_date)
                            • {{ $item->event_date->translatedFormat('d M Y') }}
                        @endif
                    </p>
                    @if ($item->caption)
                        <p class="mt-2 text-sm text-slate-600">{{ $item->caption }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                    Belum ada foto galeri pada filter ini.
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $galleryItems->links() }}</div>
    </section>
@endsection
