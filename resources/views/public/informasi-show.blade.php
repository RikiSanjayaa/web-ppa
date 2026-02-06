@extends('layouts.public')

@section('title', ($type === 'document' ? 'Dokumen' : 'Berita').' | '.$settings['site_name'])
@section('meta_description', $type === 'document' ? ($item->summary ?: $item->title) : ($item->excerpt ?: Str::limit(strip_tags($item->content), 150)))

@section('content')
    <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8" data-aos="fade-up">
        <a href="{{ route('informasi.index') }}" class="text-sm font-semibold text-coral-600">← Kembali ke Informasi</a>

        @if ($type === 'document')
            <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-coral-600">Dokumen Hukum</p>
            <h1 class="mt-2 font-heading text-3xl font-bold text-navy-700">{{ $item->title }}</h1>
            <p class="mt-2 text-sm text-slate-500">{{ $item->category }} @if($item->year) • {{ $item->year }} @endif @if($item->number) • {{ $item->number }} @endif</p>
            @if ($item->summary)
                <p class="mt-4 text-slate-700">{{ $item->summary }}</p>
            @endif
            <a href="{{ route('informasi.documents.download', $item) }}" class="btn mt-6 border-0 bg-coral-500 text-white hover:bg-coral-600">Unduh PDF</a>
        @else
            <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-coral-600">{{ $item->type }}</p>
            <h1 class="mt-2 font-heading text-3xl font-bold text-navy-700">{{ $item->title }}</h1>
            <p class="mt-2 text-sm text-slate-500">{{ optional($item->published_at)->translatedFormat('d F Y H:i') }}</p>
            @if ($item->featured_image_path)
                <img src="{{ Storage::url($item->featured_image_path) }}" alt="{{ $item->title }}" class="mt-5 h-72 w-full rounded-2xl object-cover">
            @endif
            <article class="prose mt-6 max-w-none text-slate-700 prose-headings:font-heading prose-headings:text-navy-700 prose-a:text-coral-600">
                {!! nl2br(e($item->content)) !!}
            </article>
        @endif
    </section>
@endsection
