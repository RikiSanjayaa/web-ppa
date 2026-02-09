@extends('layouts.public')

@section('title', 'Informasi | ' . $settings['site_name'])
@section('meta_description', 'Daftar UU/peraturan yang dapat diunduh serta berita/event terbaru.')

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8" data-aos="fade-up">
        <h1 class="font-heading text-3xl font-bold text-navy-700">Informasi Hukum</h1>
        <p class="mt-2 text-slate-600">Akses dokumen UU/peraturan resmi dan berita terbaru kegiatan layanan.</p>

        <form method="GET" action="{{ route('informasi.index') }}" class="mt-5 grid gap-3 lg:grid-cols-5 items-end">
            <div class="lg:col-span-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari dokumen..."
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
            </div>
            <div class="lg:col-span-2">
                <select name="category"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="btn btn-sm rounded-lg border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('informasi.index') }}"
                    class="btn btn-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </section>

    <section class="mt-8" data-aos="fade-up">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Dokumen UU / Peraturan</h2>
        <div class="mt-4 space-y-3">
            @forelse ($documents as $document)
                <article class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="font-heading text-lg font-semibold text-slate-800">{{ $document->title }}</h3>
                            <p class="text-sm text-slate-500">{{ $document->category }} @if($document->year) •
                            {{ $document->year }} @endif @if($document->number) • {{ $document->number }} @endif</p>
                            @if ($document->summary)
                                <p class="mt-2 text-sm text-slate-600">{{ $document->summary }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('informasi.show', $document->slug) }}"
                                class="btn btn-sm border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Detail</a>
                            <a href="{{ route('informasi.documents.download', $document) }}"
                                class="btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">Unduh PDF</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500">
                    Belum ada dokumen yang dipublikasikan.
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $documents->links() }}</div>
    </section>

    {{-- FAQ Section --}}
    @if($faqs->count() > 0)
        <section class="mt-12" data-aos="fade-up">
            <h2 class="font-heading text-2xl font-semibold text-navy-700">Pertanyaan Umum (FAQ)</h2>
            <p class="mt-2 text-slate-600">Jawaban untuk pertanyaan yang sering diajukan.</p>

            <div class="mt-6 grid gap-3">
                @foreach($faqs as $index => $faq)
                    <x-faq-item :question="$faq->question" :answer="$faq->answer" :open="$index === 0" />
                @endforeach
            </div>
        </section>
    @endif
@endsection