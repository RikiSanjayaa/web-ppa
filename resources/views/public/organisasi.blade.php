@extends('layouts.public')

@section('title', 'Organisasi | ' . $settings['site_name'])
@section('meta_description', 'Profil organisasi, visi misi, dan struktur pimpinan PPA/PPO.')

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


    <section class="mt-16 mb-20" data-aos="fade-up">
        <div class="mb-10 text-center sm:text-left">
            <h2 class="font-heading text-3xl font-bold text-navy-700">Struktur Organisasi</h2>
            <p class="mt-3 max-w-2xl text-lg text-slate-600">Bagan struktur organisasi Ditres PPA PPO Polda NTB.</p>
        </div>


        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm lg:p-8">
            <div class="overflow-x-auto pb-4">
                <div class="min-w-[820px] py-6 px-4">


                    <div class="flex flex-col items-center">

                        <div class="relative border-[3px] border-black bg-white text-center shadow-sm">
                            <p class="px-8 py-2 text-sm font-extrabold tracking-wide">DIRRES PPA DAN PPO</p>
                            <div class="border-t-[2px] border-black"></div>
                            <p class="px-8 py-2 text-sm font-extrabold tracking-wide">WADIR</p>
                        </div>

                        <div class="w-[3px] h-10 bg-black"></div>
                    </div>


                    <div class="relative">
                        <div class="border-t-[2px] border-dashed border-black"></div>
                        <span class="absolute right-0 -top-5 text-[11px] font-bold tracking-wide text-gray-700 italic">UNSUR PIMPINAN</span>
                    </div>


                    <div class="flex justify-center">

                        <div class="relative flex w-full max-w-[750px]">
                            <div class="absolute top-0 left-[16.66%] right-[16.66%] h-[3px] bg-black"></div>


                            <div class="flex flex-1 flex-col items-center">
                                <div class="w-[3px] h-8 bg-black"></div>
                                <div class="border-[3px] border-black bg-white px-4 py-2 text-center shadow-sm">
                                    <p class="text-xs font-extrabold tracking-wide">BAGWASSIDIK</p>
                                </div>
                                <div class="w-[3px] h-6 bg-black"></div>
                                <div class="border-[3px] border-black bg-white px-5 py-1.5 text-center shadow-sm">
                                    <p class="text-[11px] font-bold tracking-wide">UNIT</p>
                                </div>
                            </div>


                            <div class="flex flex-1 flex-col items-center">
                                <div class="w-[3px] h-8 bg-black"></div>
                                <div class="border-[3px] border-black bg-white px-4 py-2 text-center shadow-sm">
                                    <p class="text-xs font-extrabold tracking-wide">BAGBINOPSNAL</p>
                                </div>
                                <div class="w-[3px] h-6 bg-black"></div>
                                <div class="relative flex gap-0">
                                    <div class="absolute top-0 left-1/4 right-1/4 h-[3px] bg-black"></div>
                                    <div class="flex flex-col items-center px-1">
                                        <div class="w-[3px] h-4 bg-black"></div>
                                        <div class="border-[3px] border-black bg-white px-2 py-1.5 text-center shadow-sm">
                                            <p class="text-[10px] font-bold tracking-wide leading-tight">SUBBAGMIN<br>OPSNAL</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center px-1">
                                        <div class="w-[3px] h-4 bg-black"></div>
                                        <div class="border-[3px] border-black bg-white px-2 py-1.5 text-center shadow-sm">
                                            <p class="text-[10px] font-bold tracking-wide">SUBBAGANEV</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="flex flex-1 flex-col items-center">
                                <div class="w-[3px] h-8 bg-black"></div>
                                <div class="border-[3px] border-black bg-white px-4 py-2 text-center shadow-sm">
                                    <p class="text-xs font-extrabold tracking-wide">SUBBAGRENMIN</p>
                                </div>
                                <div class="w-[3px] h-6 bg-black"></div>
                                <div class="relative flex gap-0">
                                    <div class="absolute top-0 left-[16.66%] right-[16.66%] h-[3px] bg-black"></div>
                                    <div class="flex flex-col items-center px-1">
                                        <div class="w-[3px] h-4 bg-black"></div>
                                        <div class="border-[3px] border-black bg-white px-3 py-1.5 text-center shadow-sm">
                                            <p class="text-[10px] font-bold tracking-wide">URREN</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center px-1">
                                        <div class="w-[3px] h-4 bg-black"></div>
                                        <div class="border-[3px] border-black bg-white px-3 py-1.5 text-center shadow-sm">
                                            <p class="text-[10px] font-bold tracking-wide">URMINTU</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-center px-1">
                                        <div class="w-[3px] h-4 bg-black"></div>
                                        <div class="border-[3px] border-black bg-white px-3 py-1.5 text-center shadow-sm">
                                            <p class="text-[10px] font-bold tracking-wide">URKEU</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-center">
                        <div class="w-[3px] h-16 bg-black"></div>
                    </div>


                    <div class="relative">
                        <div class="border-t-[2px] border-dashed border-black"></div>
                        <span class="absolute right-0 -top-5 text-[11px] font-bold tracking-wide text-gray-700 italic">UNSUR PEMBANTU PIMPINAN/PELAYAN</span>
                    </div>


                    <div class="flex justify-center">
                        <div class="relative flex w-full max-w-[550px]">

                            <div class="absolute top-0 left-[16.66%] right-[16.66%] h-[3px] bg-black"></div>

                            @php
                                $subdits = ['SUBDIT I', 'SUBDIT II', 'SUBDIT III'];
                            @endphp

                            @foreach ($subdits as $subdit)
                                <div class="flex flex-1 flex-col items-center">
                                    <div class="w-[3px] h-8 bg-black"></div>
                                    <div class="border-[3px] border-black bg-white px-4 py-2 text-center shadow-sm">
                                        <p class="text-xs font-extrabold tracking-wide">{{ $subdit }}</p>
                                    </div>
                                    <div class="w-[3px] h-5 bg-black"></div>
                                    <div class="border-[3px] border-black bg-white px-5 py-1.5 text-center shadow-sm">
                                        <p class="text-[11px] font-bold tracking-wide">UNIT</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="relative mt-6">
                        <div class="border-t-[2px] border-dashed border-black"></div>
                        <span class="absolute right-0 -top-5 text-[11px] font-bold tracking-wide text-gray-700 italic">UNSUR PELAKSANA TUGAS POKOK</span>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection