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
@endphp

<article {{ $attributes->merge(['class' => 'group flex flex-col h-full rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md overflow-hidden']) }}>
    @if(count($imageUrls) > 0)
        <div class="relative aspect-video w-full overflow-hidden bg-slate-100"
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
            @if($post->published_at)
            <span class="text-xs text-slate-500">{{ $post->published_at->translatedFormat('d M Y') }}</span>
            @endif
        </div>

        <h3 class="mt-3 font-heading text-lg font-semibold text-slate-800 line-clamp-2 group-hover:text-navy-700">
            {{ $post->title }}
        </h3>

        <p class="mt-2 text-sm text-slate-600 line-clamp-3">{{ $excerpt }}</p>

        <a href="{{ route('informasi.show', $post->slug) }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-navy-700 hover:text-navy-800">
            Baca selengkapnya
            <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</article>
