@props([
    'post' => null,
])

@php
    $imagePaths = $post->image_paths ?? [];
    $featured = $post->featured_image_path ? Storage::url($post->featured_image_path) : null;
    
    // Fallback if image_paths is empty but featured exists
    if (empty($imagePaths) && $featured) {
        $imageUrls = [$featured];
    } elseif (!empty($imagePaths)) {
        $imageUrls = array_map(fn($path) => Storage::url($path), $imagePaths);
    } else {
        $imageUrls = [];
    }

    $excerpt = $post->excerpt ?: Str::limit(strip_tags($post->content), 120);

    // For Instagram posts, extract the embed URL
    $igEmbedUrl = null;
    if ($post->isInstagram() && $post->instagram_url && count($imageUrls) === 0) {
        if (preg_match('#instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_-]+)#', $post->instagram_url, $igMatch)) {
            $igEmbedUrl = 'https://www.instagram.com/p/' . $igMatch[1] . '/embed/';
        }
    }
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col h-full rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md overflow-hidden']) }}>
    @if($igEmbedUrl)
        {{-- Instagram Embed for posts without scraped images --}}
        <div class="relative aspect-square w-full overflow-hidden bg-slate-50">
            <iframe src="{{ $igEmbedUrl }}" 
                    class="absolute inset-0 h-full w-full border-0" 
                    allowtransparency="true" 
                    scrolling="no"
                    loading="lazy">
            </iframe>
        </div>
    @elseif(count($imageUrls) > 0)

        <div class="relative aspect-square w-full overflow-hidden bg-slate-100"
             x-data="{ 
                active: 0, 
                images: {{ json_encode($imageUrls) }},
                timer: null
             }"
             x-init="if(images.length > 1) { timer = setInterval(() => active = (active + 1) % images.length, 3000) }"
             @mouseenter="if(timer) clearInterval(timer)"
             @mouseleave="if(images.length > 1) timer = setInterval(() => active = (active + 1) % images.length, 3000)"
        >
            <template x-for="(img, index) in images" :key="index">
                <img :src="img" 
                     :alt="'{{ $post->title }} - Image ' + (index + 1)" 
                     class="absolute inset-0 h-full w-full object-cover transition-opacity duration-700 ease-in-out"
                     :class="active === index ? 'opacity-100' : 'opacity-0'"
                >
            </template>
            
            <template x-if="images.length > 1">
                <div class="pointer-events-none absolute inset-0 flex items-center justify-between p-2 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                    <button @click.prevent="active = (active - 1 + images.length) % images.length" 
                            class="pointer-events-auto rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition hover:bg-black/50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click.prevent="active = (active + 1) % images.length" 
                            class="pointer-events-auto rounded-full bg-black/30 p-2 text-white backdrop-blur-sm transition hover:bg-black/50">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>

            {{-- Dots Indicator (Only if multiple) --}}
            <template x-if="images.length > 1">
                <div class="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1.5 pointer-events-none">
                    <template x-for="(img, index) in images" :key="index">
                        <div class="h-1.5 rounded-full transition-all duration-300 pointer-events-auto cursor-pointer" 
                             @click="active = index"
                             :class="active === index ? 'w-4 bg-white' : 'w-1.5 bg-white/50'"></div>
                    </template>
                </div>
            </template>
        </div>
    @endif

    <div class="flex flex-1 flex-col p-4 lg:p-5">
        <div class="flex items-center gap-2">
            <span class="inline-block rounded-full bg-navy-100 px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide text-navy-700">
                {{ $post->type }}
            </span>
            @if($post->isInstagram())
            <span class="inline-flex items-center gap-0.5 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 px-2 py-0.5 text-[10px] font-semibold text-white">
                <svg class="h-2.5 w-2.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                IG
            </span>
            @endif
            @if($post->published_at)
            <span class="text-xs text-slate-500">{{ $post->published_at->translatedFormat('d M Y') }}</span>
            @endif
        </div>

        <h3 class="mt-3 font-heading text-lg font-semibold text-slate-800 line-clamp-2 group-hover:text-navy-700">
            {{ $post->title }}
        </h3>

        <p class="mt-2 text-sm text-slate-600 line-clamp-3">{{ $excerpt }}</p>

        @if($post->isInstagram() && $post->instagram_url)
        <a href="{{ $post->instagram_url }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-pink-600 hover:text-pink-700">
            Lihat di Instagram
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
        @else
        <a href="{{ route('informasi.show', $post->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-navy-700 hover:text-navy-800">
            Baca selengkapnya
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @endif
    </div>
</article>
