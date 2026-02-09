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
            @php
                $imagePaths = $item->image_paths ?? [];
                $featured = $item->featured_image_path ? Storage::url($item->featured_image_path) : null;
                
                if (empty($imagePaths) && $featured) {
                    $imageUrls = [$featured];
                } elseif (!empty($imagePaths)) {
                    $imageUrls = array_map(fn($path) => Storage::url($path), $imagePaths);
                } else {
                    $imageUrls = [];
                }
            @endphp

            @if (count($imageUrls) > 0)
                <div class="group relative mt-6 w-full overflow-hidden rounded-2xl bg-slate-100"
                     x-data="{ 
                        active: 0, 
                        images: {{ json_encode($imageUrls) }},
                        timer: null
                     }"
                     x-init="if(images.length > 1) { timer = setInterval(() => active = (active + 1) % images.length, 4000) }"
                     @mouseenter="if(timer) clearInterval(timer)"
                     @mouseleave="if(images.length > 1) timer = setInterval(() => active = (active + 1) % images.length, 4000)"
                >
                    <!-- Main Image (Aspect Ratio 16:9 or max-height) -->
                    <div class="aspect-video w-full md:aspect-[21/9] md:max-h-[500px]">
                        <template x-for="(img, index) in images" :key="index">
                            <img :src="img" 
                                 :alt="'{{ $item->title }} - Image ' + (index + 1)" 
                                 class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700 ease-in-out"
                                 :class="active === index ? 'opacity-100' : 'opacity-0'"
                            >
                        </template>
                    </div>

                    <!-- Navigation Arrows -->
                    <template x-if="images.length > 1">
                        <div class="pointer-events-none absolute inset-0 flex items-center justify-between p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <button @click.prevent="active = (active - 1 + images.length) % images.length" 
                                    class="pointer-events-auto rounded-full bg-black/30 p-3 text-white backdrop-blur-sm transition hover:bg-black/50 hover:scale-110">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button @click.prevent="active = (active + 1) % images.length" 
                                    class="pointer-events-auto rounded-full bg-black/30 p-3 text-white backdrop-blur-sm transition hover:bg-black/50 hover:scale-110">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </template>

                    <!-- Dots Indicator -->
                    <template x-if="images.length > 1">
                        <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-2 rounded-full bg-black/20 px-3 py-1.5 backdrop-blur-sm hover:bg-black/40 transition-colors">
                            <template x-for="(img, index) in images" :key="index">
                                <button class="h-2 rounded-full transition-all duration-300 focus:outline-none" 
                                     @click="active = index"
                                     :class="active === index ? 'w-6 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"></button>
                            </template>
                        </div>
                    </template>
                </div>
            @endif
            <article class="prose mt-6 max-w-none text-slate-700 prose-headings:font-heading prose-headings:text-navy-700 prose-a:text-coral-600">
                {!! nl2br(e($item->content)) !!}
            </article>
        @endif
    </section>
@endsection
