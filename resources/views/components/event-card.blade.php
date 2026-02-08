@props([
    'post' => null,
])

@php
    $imageUrl = $post->featured_image_path ? Storage::url($post->featured_image_path) : null;
    $excerpt = $post->excerpt ?: Str::limit(strip_tags($post->content), 120);
@endphp

<article {{ $attributes->merge(['class' => 'group rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md overflow-hidden']) }}>
    @if($imageUrl)
    <div class="aspect-video w-full overflow-hidden bg-slate-100">
        <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
    </div>
    @endif

    <div class="p-4 lg:p-5">
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
