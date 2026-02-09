@props([
    'title' => 'Perlindungan Perempuan dan Anak',
    'subtitle' => 'Layanan aduan cepat, aman, dan responsif untuk masyarakat.',
    'ctaText' => 'Laporkan via WhatsApp',
    'ctaUrl' => '#',
    'hotlineNumber' => '',
    'stats' => [],
])

<section
    class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-navy-700 via-navy-600 to-teal-600 px-6 py-14 text-white lg:px-12 lg:py-20">
    <div class="relative z-10 grid gap-10 lg:grid-cols-2 lg:items-center">
        <div data-aos="fade-up">
            <span
                class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide backdrop-blur">
                <svg class="h-4 w-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Layanan Resmi PPA/PPO

            </span>


            <h1 class="font-heading text-3xl font-bold leading-tight lg:text-5xl">{{ $title }}</h1>
            <p class="mt-4 text-base leading-relaxed text-slate-100 lg:text-lg">{{ $subtitle }}</p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" data-hotline-track
                    class="btn btn-lg gap-2 border-0 bg-[#25D366] text-white shadow-lg shadow-green-500/30 transition-all hover:bg-[#1fba59] hover:shadow-xl hover:shadow-green-500/40">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>

                    {{ $ctaText }}
                </a>

                @if ($hotlineNumber)
                    <div class="flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 backdrop-blur">
                        <svg class="h-4 w-4 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-sm font-medium">{{ $hotlineNumber }}</span>
                    </div>
                @endif
            </div>
        </div>


        @if (count($stats) > 0)
            <div class="grid grid-cols-2 gap-3" data-aos="fade-left" data-aos-delay="100">
                @foreach ($stats as $label => $value)
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm transition hover:bg-white/15">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-200">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-bold lg:text-3xl">
                            {{ is_numeric($value) ? number_format($value) : $value }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
