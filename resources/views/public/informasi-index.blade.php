@extends('layouts.public')

@section('title', 'Informasi | ' . $settings['site_name'])
@section('meta_description', 'Daftar UU/peraturan yang dapat diunduh serta berita/event terbaru.')

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8" data-aos="fade-up"
        x-data="{ showPdfModal: false, pdfUrl: '', pdfTitle: '', downloadUrl: '' }">
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

        {{-- Dokumen Section --}}
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
                                <button type="button"
                                    @click="pdfUrl = '{{ route('informasi.documents.preview', $document) }}'; pdfTitle = '{{ addslashes($document->title) }}'; downloadUrl = '{{ route('informasi.documents.download', $document) }}'; showPdfModal = true"
                                    class="btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">
                                    <svg class="inline-block h-4 w-4 mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat PDF
                                </button>
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

        {{-- PDF Preview Modal --}}
        <div x-show="showPdfModal" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="showPdfModal = false; pdfUrl = ''"
            class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm">

            <div class="fixed inset-0 z-[90] flex flex-col">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between bg-navy-800 px-4 py-3 text-white shadow-lg sm:px-6">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="h-6 w-6 flex-shrink-0 text-coral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="truncate font-heading text-sm font-semibold sm:text-base" x-text="pdfTitle"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="downloadUrl"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-coral-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-coral-600 sm:text-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh
                        </a>
                        <button @click="showPdfModal = false; pdfUrl = ''"
                            class="rounded-lg bg-white/10 p-2 text-white transition hover:bg-white/20">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- PDF Iframe --}}
                <div class="flex-1 bg-slate-900">
                    <iframe x-show="pdfUrl" :src="pdfUrl" class="h-full w-full border-0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
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