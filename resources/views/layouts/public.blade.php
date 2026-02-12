<!DOCTYPE html>
<html lang="id" data-theme="ppawarm">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $settings['site_name'] . ' | Perlindungan Perempuan & Anak')</title>
    <meta name="description" content="@yield('meta_description', $settings['hero_subtitle'])">
    <meta property="og:title" content="@yield('og_title', $settings['hero_title'])">
    <meta property="og:description" content="@yield('og_description', $settings['hero_subtitle'])">
    <meta property="og:type" content="website">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 font-body text-slate-800">
    <div x-data="{ 
        showConsultationModal: false,
        init() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('open_consultation')) {
                this.showConsultationModal = true;
            }
        }
    }" 
    x-init="init()"
    @keydown.escape.window="showConsultationModal = false"
    >
        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-full max-w-lg px-4">
            <div class="flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 p-4 shadow-lg">
                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-500 text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
             class="fixed top-4 left-1/2 -translate-x-1/2 z-[100] w-full max-w-lg px-4">
            <div class="flex items-center gap-3 rounded-xl bg-amber-50 border border-amber-200 p-4 shadow-lg">
                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-amber-500 text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                </div>
                <p class="text-sm font-semibold text-amber-800">{{ session('error') }}</p>
                <button @click="show = false" class="ml-auto text-amber-400 hover:text-amber-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Header Navigation --}}
        <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 text-lg font-heading font-bold text-navy-700">
                    <img src="{{ asset('logo.png') }}" alt="Logo {{ $settings['site_name'] }}"
                        class="h-10 w-10 rounded-full object-cover">
                    <span>{{ $settings['site_name'] }}</span>
                </a>
                <nav class="hidden items-center gap-2 lg:flex">
                    <a class="nav-pill {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                    <a class="nav-pill {{ request()->routeIs('organisasi') ? 'nav-pill-active' : '' }}"
                        href="{{ route('organisasi') }}">Organisasi</a>
                    <a class="nav-pill {{ request()->routeIs('layanan-masyarakat') ? 'nav-pill-active' : '' }}"
                        href="{{ route('layanan-masyarakat') }}">Layanan Masyarakat</a>
                    <a class="nav-pill {{ request()->routeIs('informasi.*') ? 'nav-pill-active' : '' }}"
                        href="{{ route('informasi.index') }}">Informasi</a>
                    <a class="nav-pill {{ request()->routeIs('galeri.*') ? 'nav-pill-active' : '' }}"
                        href="{{ route('galeri.index') }}">Galeri</a>
                </nav>
                <button @click="showConsultationModal = true"
                    class="hidden lg:inline-flex btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">
                    Konsultasi via WA
                </button>
            </div>
            <nav class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 pb-3 lg:hidden lg:px-8">
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}"
                    href="{{ route('home') }}">Home</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('organisasi') ? 'nav-pill-active' : '' }}"
                    href="{{ route('organisasi') }}">Organisasi</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('layanan-masyarakat') ? 'nav-pill-active' : '' }}"
                    href="{{ route('layanan-masyarakat') }}">Layanan</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('informasi.*') ? 'nav-pill-active' : '' }}"
                    href="{{ route('informasi.index') }}">Informasi</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('galeri.*') ? 'nav-pill-active' : '' }}"
                    href="{{ route('galeri.index') }}">Galeri</a>
            </nav>
        </header>
    
        <main class="mx-auto min-h-[70vh] max-w-7xl px-4 py-8 lg:px-8">
            @if (session('status'))
                <div class="alert mb-6 border border-teal-200 bg-teal-50 text-teal-800">
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @yield('content')
        </main>
    
        <footer class="border-t border-slate-200 bg-white/90 py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 text-sm lg:grid-cols-4 lg:px-8">
                <div>
                    <p class="font-heading text-base font-semibold text-navy-700">{{ $settings['site_name'] }}</p>
                    <p class="mt-2 text-slate-600">{{ $settings['hero_subtitle'] }}</p>
                </div>
                <div>
                    <p class="font-semibold text-navy-700">Hubungi Kami</p>
                    <p class="mt-2 text-slate-600">{{ $settings['contact_address'] }}</p>
                    <p class="text-slate-600">{{ $settings['contact_email'] }}</p>
                </div>
                <div>
                    <p class="font-semibold text-navy-700">Akses Cepat</p>
                    <ul class="mt-2 space-y-1 text-slate-600">
                        <li><a href="{{ route('layanan-masyarakat') }}" class="hover:text-navy-700">Form Pengaduan</a></li>
                        <li><a href="{{ route('informasi.index') }}" class="hover:text-navy-700">Dokumen UU</a></li>
                        <li><a href="{{ route('galeri.index') }}" class="hover:text-navy-700">Galeri Kegiatan</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold text-navy-700 mb-2">Ikuti Kami</p>
                    <div class="flex gap-4">
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener"
                                class="text-slate-400 hover:text-pink-600 transition-colors">
                                <span class="sr-only">Instagram</span>
                                <x-social-icon name="instagram" />
                            </a>
                        @endif
    
                        @if(!empty($settings['tiktok_url']))
                            <a href="{{ $settings['tiktok_url'] }}" target="_blank" rel="noopener"
                                class="text-slate-400 hover:text-black transition-colors">
                                <span class="sr-only">TikTok</span>
                                <x-social-icon name="tiktok" />
                            </a>
                        @endif
    
                        @if(!empty($settings['facebook_url']))
                            <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener"
                                class="text-slate-400 hover:text-blue-600 transition-colors">
                                <span class="sr-only">Facebook</span>
                                <x-social-icon name="facebook" />
                            </a>
                        @endif
    
                        @if(!empty($settings['youtube_url']))
                            <a href="{{ $settings['youtube_url'] }}" target="_blank" rel="noopener"
                                class="text-slate-400 hover:text-red-600 transition-colors">
                                <span class="sr-only">YouTube</span>
                                <x-social-icon name="youtube" />
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </footer>

        {{-- Global Consultation Modal --}}
        <div x-show="showConsultationModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[80] bg-black/50 backdrop-blur-sm"
            style="display: none;">
            
            <div class="fixed inset-0 z-[90] overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200"
                        @click.outside="showConsultationModal = false"
                        x-show="showConsultationModal"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        
                        {{-- Modal Header --}}
                        <div class="bg-white px-6 py-6 lg:px-8 flex justify-between items-start border-b border-slate-100">
                            <div>
                                <h3 class="font-heading text-2xl font-bold text-navy-700" id="modal-title">Form Konsultasi</h3>
                                <p class="mt-1 text-sm text-slate-600">Sampaikan konsultasi Anda kepada kami. Privasi terjaga.</p>
                            </div>
                            <button @click="showConsultationModal = false" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
    
                        {{-- Modal Body --}}
                        <div class="px-6 py-6 lg:px-8">
                            <form method="POST" action="{{ route('consultations.store') }}" class="grid gap-4">
                                @csrf
                                <div>
                                    <input type="text" name="nama_klien" required
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                                        placeholder="Masukkan nama Anda">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700">No HP / WA *</label>
                                    <input type="text" name="no_hp" required
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                                        placeholder="Contoh: 08123456789">
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-semibold text-slate-700">Permasalahan *</label>
                                    <textarea name="permasalahan" rows="6" required
                                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                                        placeholder="Ceritakan permasalahan Anda secara lengkap..."></textarea>
                                </div>
                                <div class="flex justify-end gap-3 mt-4">
                                    <button type="button" @click="showConsultationModal = false" class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">
                                        Kirim Konsultasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Floating WhatsApp Button --}}
        <x-whatsapp-button :number="$settings['hotline_wa_number']" />
    
        @stack('scripts')
    </div>
</body>

</html>