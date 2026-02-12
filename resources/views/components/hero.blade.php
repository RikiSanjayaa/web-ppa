@props([
    'title' => 'Perlindungan Perempuan dan Anak',
    'subtitle' => 'Layanan aduan cepat, aman, dan responsif untuk masyarakat.',
    'ctaText' => 'Laporkan via WhatsApp',
    'ctaUrl' => '#',
    'secondaryCtaText' => '',
    'secondaryCtaUrl' => '',
    'secondaryCtaAction' => null,
    'hotlineNumber' => '',
    'stats' => [],
])

<section
    class="relative overflow-hidden rounded-3xl bg-gray-900 px-6 py-14 text-white lg:px-12 lg:py-20">
    <!-- Background Slideshow -->
    <div class="absolute inset-0 z-0"
         x-data="{ 
            active: 0, 
            images: [
                '{{ asset('images/Profil1.jpeg') }}', 
                '{{ asset('images/Profil2.jpeg') }}', 
                '{{ asset('images/Profil3.jpeg') }}', 
                '{{ asset('images/Profil4.jpeg') }}', 
                '{{ asset('images/Profil5.jpeg') }}'
            ] 
         }"
         x-init="setInterval(() => { active = (active + 1) % images.length }, 5000)">
        
        <template x-for="(img, index) in images" :key="index">
            <img :src="img" 
                 alt="Background" 
                 class="absolute inset-0 h-full w-full object-cover transition-opacity duration-1000 ease-in-out"
                 :class="active === index ? 'opacity-100' : 'opacity-0'">
        </template>
        
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/60 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 grid gap-10 lg:grid-cols-2 lg:items-center">
        <div data-aos="fade-up">
            <span
                class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide backdrop-blur border border-white/20">
                <svg class="h-4 w-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Layanan Resmi PPA/PPO
            </span>

            <h1 class="font-heading text-4xl font-bold leading-tight lg:text-5xl text-white drop-shadow-lg">{{ $title }}</h1>
            <p class="mt-6 text-lg leading-relaxed text-slate-200 lg:text-xl drop-shadow-md max-w-lg">{{ $subtitle }}</p>

            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ $ctaUrl }}" 
                    class="btn btn-lg gap-2 border-0 bg-coral-600 text-white shadow-lg shadow-coral-500/30 transition-transform hover:scale-105 hover:bg-coral-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>

                    {{ $ctaText }}
                </a>

                @if ($secondaryCtaText && ($secondaryCtaUrl || $secondaryCtaAction))
                    <a href="{{ $secondaryCtaUrl ?: '#' }}" 
                        @if($secondaryCtaAction) @click.prevent="{{ $secondaryCtaAction }}" @endif
                        class="btn btn-lg gap-2 border-0 bg-teal-500 text-white shadow-lg shadow-teal-500/30 transition-transform hover:scale-105 hover:bg-teal-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        {{ $secondaryCtaText }}
                    </a>
                @endif

                @if ($hotlineNumber)
                    <div class="flex items-center gap-2 rounded-full bg-white/10 px-5 py-3 backdrop-blur border border-white/20 hover:bg-white/20 transition cursor-pointer" onclick="window.location.href='tel:{{ $hotlineNumber }}'">
                        <svg class="h-5 w-5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-base font-medium text-white">{{ $hotlineNumber }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
