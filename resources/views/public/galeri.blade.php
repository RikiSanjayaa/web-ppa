@extends('layouts.public')

@section('title', 'Galeri | '.$settings['site_name'])
@section('meta_description', 'Galeri foto kegiatan PPA/PPO berdasarkan kategori dan tahun.')

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8" data-aos="fade-up">
        <h1 class="font-heading text-3xl font-bold text-navy-700">Galeri & Arsip</h1>
        <p class="mt-2 text-slate-600">Arsip berita dan dokumentasi kegiatan PPA/PPO.</p>

        <form method="GET" action="{{ route('galeri.index') }}" class="mt-5 grid gap-3 md:grid-cols-4 md:items-end">
            <label class="form-control">
                <span class="mb-1 text-sm font-semibold text-slate-700">Tipe</span>
                <select name="type" class="select select-bordered">
                    <option value="">Semua</option>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}" @selected($selectedType === $key)>{{ $label }}</option>
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
            <div class="flex gap-2">
                <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('galeri.index') }}" class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </section>

    <section class="mt-8" data-aos="fade-up">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($newsPosts as $post)
                <x-event-card :post="$post" />
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                    Belum ada berita atau event pada filter ini.
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $newsPosts->links() }}</div>
    </section>
@endsection
