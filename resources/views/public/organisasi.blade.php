@extends('layouts.public')

@section('title', 'Organisasi | ' . $settings['site_name'])
@section('meta_description', 'Profil organisasi, visi misi, dan struktur atasan PPA/PPO.')

@section('content')
    <section class="rounded-3xl bg-white p-6 shadow-sm lg:p-10" data-aos="fade-up">
        <p class="text-xs font-semibold uppercase tracking-wide text-coral-600">Profil Organisasi</p>
        <h1 class="mt-2 font-heading text-3xl font-bold text-navy-700">Struktur dan Kepemimpinan</h1>
        <p class="mt-4 text-slate-700">{{ $settings['organization_profile'] }}</p>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="font-heading text-xl font-semibold text-navy-700">Visi</h2>
                <p class="mt-2 text-slate-700">{{ $settings['organization_vision'] }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="font-heading text-xl font-semibold text-navy-700">Misi</h2>
                <ul class="mt-2 space-y-1 text-slate-700">
                    @foreach (preg_split('/\r\n|\r|\n/', $settings['organization_mission']) as $mission)
                        @if (trim($mission) !== '')
                            <li>{{ $mission }}</li>
                        @endif
                    @endforeach
                </ul>
            </article>
        </div>
    </section>

    <section class="mt-16" data-aos="fade-up">
        <div class="mb-10 text-center sm:text-left">
            <h2 class="font-heading text-3xl font-bold text-navy-700">Daftar Pimpinan</h2>
            <p class="mt-3 max-w-2xl text-lg text-slate-600">{{ $settings['organization_structure'] }}</p>
        </div>

        <div class="relative group/section">
            <button
                class="leaders-prev absolute left-0 top-1/2 z-30 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-md ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-left-4 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="leaders-next absolute right-0 top-1/2 z-30 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-md ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-right-4 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="swiper leaders-swiper !py-10 !px-1">
                <div class="swiper-wrapper">
                    @forelse ($leaders as $leader)
                        <div class="swiper-slide h-auto w-full max-w-[280px]">
                            <div class="group/card relative aspect-[3/4] w-full overflow-hidden rounded-3xl bg-slate-900 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl transform-gpu will-change-transform">
                                @if ($leader->photo_path)
                                    <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover/card:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-slate-800 text-slate-500">
                                        <svg class="h-16 w-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 transition-opacity duration-300 group-hover/card:opacity-80"></div>

                                <div class="absolute inset-x-0 bottom-0 p-5 text-left">
                                    <div class="transform transition-transform duration-300 group-hover/card:-translate-y-1">
                                        <div class="mb-1.5">
                                            <span class="inline-block rounded-md bg-white/20 px-2 py-0.5 text-[10px] font-bold tracking-wider text-white backdrop-blur-sm uppercase">
                                                {{ $leader->position }}
                                            </span>
                                        </div>
                                        <div class="inline-block">
                                            <h3 class="font-heading text-lg font-bold text-white leading-tight">
                                                {{ $leader->name }}
                                            </h3>
                                            <div class="mt-1.5 h-[1.5px] w-full rounded-full bg-coral-500 opacity-0 transition-opacity duration-300 group-hover/card:opacity-100"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-rows-[0fr] transition-all duration-300 ease-out group-hover/card:grid-rows-[1fr]">
                                        <div class="overflow-hidden">
                                            <div class="pt-2 opacity-0 transition-opacity duration-300 delay-75 group-hover/card:opacity-100">
                                                @if ($leader->bio)
                                                    <p class="text-xs leading-relaxed text-slate-200 line-clamp-3">
                                                        {{ $leader->bio }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-slate-400 italic">Tidak ada deskripsi singkat.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide w-full">
                            <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center h-64">
                                <p class="text-sm text-slate-500">Belum ada data pimpinan.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection