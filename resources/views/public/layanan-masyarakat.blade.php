@extends('layouts.public')

@section('title', 'Layanan Masyarakat | ' . $settings['site_name'])
@section('meta_description', 'Form aduan resmi, hotline WhatsApp, dan testimoni layanan masyarakat PPA/PPO.')

@push('head')
    @if (config('services.turnstile.enabled'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endpush

@section('content')
    <section class="grid gap-6 lg:grid-cols-3" data-aos="fade-up">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-2">
            <p class="text-xs font-semibold uppercase tracking-wide text-coral-600">Hubungi Kami</p>
            <h1 class="mt-2 font-heading text-3xl font-bold text-navy-700">Layanan Masyarakat</h1>
            <p class="mt-3 text-slate-600">Untuk laporan darurat, silakan langsung hubungi hotline WhatsApp. Untuk
                pencatatan resmi, gunakan form pengaduan di bawah.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <button @click="showConsultationModal = true"
                    class="btn border-0 bg-coral-500 text-white hover:bg-coral-600">Konsultasi via WA</button>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="font-heading text-xl font-semibold text-navy-700">Kontak Layanan</h2>
            <p class="mt-3 text-sm text-slate-600">{{ $settings['contact_address'] }}</p>
            <p class="mt-2 text-sm text-slate-600">Email: {{ $settings['contact_email'] }}</p>

            <div class="mt-5 border-t border-slate-100 pt-4">
                <p class="mb-3 text-sm font-semibold text-navy-700">Media Sosial</p>
                <div class="flex gap-4">
                    @if(!empty($settings['instagram_url']))
                        <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener"
                            class="text-slate-400 hover:text-pink-600 transition-colors">
                            <span class="sr-only">Instagram</span>
                            <x-social-icon name="instagram" class="h-5 w-5" />
                        </a>
                    @endif

                    @if(!empty($settings['tiktok_url']))
                        <a href="{{ $settings['tiktok_url'] }}" target="_blank" rel="noopener"
                            class="text-slate-400 hover:text-black transition-colors">
                            <span class="sr-only">TikTok</span>
                            <x-social-icon name="tiktok" class="h-5 w-5" />
                        </a>
                    @endif

                    @if(!empty($settings['facebook_url']))
                        <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener"
                            class="text-slate-400 hover:text-blue-600 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <x-social-icon name="facebook" class="h-5 w-5" />
                        </a>
                    @endif

                    @if(!empty($settings['youtube_url']))
                        <a href="{{ $settings['youtube_url'] }}" target="_blank" rel="noopener"
                            class="text-slate-400 hover:text-red-600 transition-colors">
                            <span class="sr-only">YouTube</span>
                            <x-social-icon name="youtube" class="h-5 w-5" />
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="form-aduan" class="mt-10 scroll-mt-48 rounded-3xl border border-slate-200 bg-white p-6 lg:p-8"
        data-aos="fade-up">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Form Pengaduan</h2>
        {{-- Banner Permintaan Lokasi (muncul saat pertama kali) --}}
        <div id="location-prompt" class="mb-4 hidden rounded-xl border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-4">
            <div class="flex items-start gap-4">
                <span class="mt-0.5 text-2xl">📍</span>
                <div class="flex-1">
                    <p class="font-semibold text-navy-700">Izinkan Akses Lokasi</p>
                    <p class="mt-1 text-sm text-slate-600">Kami membutuhkan lokasi Anda untuk mencatat posisi pengaduan. Tekan tombol di bawah, lalu pilih <strong>"Izinkan"</strong> pada dialog browser yang muncul.</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button type="button" onclick="requestGeolocation()"
                            class="inline-flex items-center gap-2 rounded-xl bg-navy-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-navy-800 transition-all hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Izinkan Lokasi
                        </button>
                        <button type="button" onclick="skipLocation()"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                            Lewati
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Lokasi (loading/sukses/error) --}}
        <div id="location-status" class="mb-4 hidden rounded-xl border px-4 py-3 text-sm font-medium">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <span id="location-message"></span>
                <div id="location-actions" class="hidden gap-2" style="display:none">
                    <button type="button" onclick="showLocationGuide()" class="rounded-lg border border-navy-300 bg-white px-3 py-1 text-xs font-semibold text-navy-700 hover:bg-navy-50 transition-colors">
                        📖 Panduan Reset Izin
                    </button>
                    <button type="button" onclick="requestGeolocation()" class="rounded-lg bg-navy-700 px-3 py-1 text-xs font-semibold text-white hover:bg-navy-800 transition-colors">
                        ↻ Coba Lagi
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal Panduan Reset Izin --}}
        <div id="location-guide-modal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="if(event.target===this)closeLocationGuide()">
            <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="font-heading text-lg font-semibold text-navy-700">📍 Reset Izin Lokasi</h3>
                    <button onclick="closeLocationGuide()" class="rounded-full p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-5">
                    <p class="text-sm text-slate-600">Izin lokasi pernah ditolak sebelumnya, sehingga dialog izin tidak muncul lagi. Ikuti langkah berikut untuk mereset:</p>

                    {{-- Chrome --}}
                    <div id="guide-chrome" class="hidden">
                        <h4 class="flex items-center gap-2 font-semibold text-slate-800">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-sm">🌐</span>
                            Google Chrome
                        </h4>
                        <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                            <li>Klik ikon <strong>gembok 🔒</strong> atau <strong>ikon info ⓘ</strong> di kiri address bar</li>
                            <li>Cari menu <strong>"Lokasi"</strong> atau <strong>"Location"</strong></li>
                            <li>Ubah dari <strong>"Blokir"</strong> menjadi <strong>"Izinkan"</strong></li>
                            <li>Halaman akan refresh otomatis — selesai!</li>
                        </ol>
                    </div>

                    {{-- Firefox --}}
                    <div id="guide-firefox" class="hidden">
                        <h4 class="flex items-center gap-2 font-semibold text-slate-800">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 text-sm">🦊</span>
                            Mozilla Firefox
                        </h4>
                        <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                            <li>Klik ikon <strong>gembok 🔒</strong> di kiri address bar</li>
                            <li>Klik <strong>"Hapus Izin dan Muat Ulang"</strong> pada bagian Lokasi</li>
                            <li>Atau: klik <strong>"Izin"</strong> → hapus centang <strong>"Blokir"</strong> pada Lokasi</li>
                            <li>Refresh halaman, lalu klik <strong>"Izinkan"</strong> saat dialog muncul</li>
                        </ol>
                    </div>

                    {{-- Edge --}}
                    <div id="guide-edge" class="hidden">
                        <h4 class="flex items-center gap-2 font-semibold text-slate-800">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-cyan-100 text-sm">🌊</span>
                            Microsoft Edge
                        </h4>
                        <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                            <li>Klik ikon <strong>gembok 🔒</strong> di kiri address bar</li>
                            <li>Klik <strong>"Izin untuk situs ini"</strong></li>
                            <li>Cari <strong>"Lokasi"</strong> dan ubah menjadi <strong>"Izinkan"</strong></li>
                            <li>Kembali ke halaman dan klik <strong>"Coba Lagi"</strong></li>
                        </ol>
                    </div>

                    {{-- Safari --}}
                    <div id="guide-safari" class="hidden">
                        <h4 class="flex items-center gap-2 font-semibold text-slate-800">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 text-sm">🧭</span>
                            Safari (macOS)
                        </h4>
                        <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                            <li>Di menu bar atas, klik <strong>Safari → Settings → Websites</strong></li>
                            <li>Pilih <strong>"Location"</strong> di sidebar kiri</li>
                            <li>Cari situs ini dan ubah menjadi <strong>"Allow"</strong> atau <strong>"Ask"</strong></li>
                            <li>Pastikan juga: <strong>System Settings → Privacy & Security → Location Services</strong> → Safari dicentang</li>
                            <li>Kembali dan klik <strong>"Coba Lagi"</strong></li>
                        </ol>
                    </div>

                    {{-- Mobile --}}
                    <div id="guide-mobile" class="hidden">
                        <h4 class="flex items-center gap-2 font-semibold text-slate-800">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-sm">📱</span>
                            Browser HP (Chrome/Safari)
                        </h4>
                        <ol class="mt-3 space-y-2 text-sm text-slate-600 list-decimal list-inside">
                            <li>Buka <strong>Pengaturan HP</strong> → <strong>Aplikasi</strong> → cari browser Anda</li>
                            <li>Klik <strong>"Izin"</strong> atau <strong>"Permissions"</strong></li>
                            <li>Aktifkan <strong>"Lokasi"</strong></li>
                            <li>Kembali ke halaman ini dan klik <strong>"Coba Lagi"</strong></li>
                        </ol>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                        <strong>💡 Tips:</strong> Setelah mengubah izin di browser, klik tombol <strong>"Coba Lagi"</strong> di halaman ini. Lokasi akan otomatis terdeteksi tanpa perlu refresh halaman.
                    </div>
                </div>
                <div class="border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                    <button onclick="closeLocationGuide()" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Tutup</button>
                    <button onclick="closeLocationGuide(); requestGeolocation()" class="rounded-xl bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-800 transition-colors">↻ Coba Lagi Sekarang</button>
                </div>
            </div>
        </div>
        <p class="mt-2 text-sm text-slate-600">Data pengaduan akan tersimpan di sistem, lalu Anda diarahkan ke WhatsApp hotline
            dengan pesan terisi otomatis.</p>

        @if ($errors->any())
            <div class="alert mt-4 border border-red-200 bg-red-50 text-red-700">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('complaints.store') }}" class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            <input type="hidden" name="latitude" id="aduan_latitude">
            <input type="hidden" name="longitude" id="aduan_longitude">

            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Lengkap Pelapor *</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Masukkan nama lengkap Anda">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">No HP / WA *</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Contoh: 08123456789">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">NIK *</label>
                <input type="text" name="nik" value="{{ old('nik') }}"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    required placeholder="Masukkan 16 digit NIK">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="alamat@email.com">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Alamat *</label>
                <textarea name="alamat" rows="2"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    required placeholder="Alamat lengkap domisili saat ini">{{ old('alamat') }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Tempat Kejadian *</label>
                <input type="text" name="tempat_kejadian" value="{{ old('tempat_kejadian') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Lokasi kejadian perkara">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Waktu Kejadian *</label>
                <input type="date" name="waktu_kejadian" value="{{ old('waktu_kejadian') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Korban *</label>
                <input type="text" name="korban" value="{{ old('korban') }}"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    required placeholder="Nama lengkap korban">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Terlapor *</label>
                <input type="text" name="terlapor" value="{{ old('terlapor') }}" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Nama terlapor">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Saksi-saksi (Opsional)</label>
                <textarea name="saksi_saksi" rows="2"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Nama-nama saksi (pisahkan dengan koma)">{{ old('saksi_saksi') }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Kronologis Singkat *</label>
                <textarea name="kronologis_singkat" rows="5" required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none"
                    placeholder="Ceritakan kronologis kejadian secara singkat dan jelas...">{{ old('kronologis_singkat') }}</textarea>
            </div>

            @if (config('services.turnstile.enabled'))
                <div class="md:col-span-2">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                </div>
            @endif

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Kirim Pengaduan &
                    Lanjut ke
                    WhatsApp</button>
            </div>
        </form>
    </section>


    <section class="mt-10" data-aos="fade-up">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Testimoni Layanan</h2>

        <div class="swiper testimonial-swiper mt-5">
            <div class="swiper-wrapper">
                @forelse ($testimonials as $testimonial)
                    <article class="swiper-slide rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="font-heading text-lg font-semibold text-slate-800">{{ $testimonial->name }}</h3>
                        @if ($testimonial->relation)
                            <p class="text-sm text-coral-600">{{ $testimonial->relation }}</p>
                        @endif
                        <p class="mt-3 text-sm text-slate-700">"{{ $testimonial->content }}"</p>
                        <p class="mt-3 text-amber-500">{{ str_repeat('★', max(1, min(5, $testimonial->rating))) }}</p>
                    </article>
                @empty
                    <article
                        class="swiper-slide rounded-2xl border border-dashed border-slate-300 bg-white p-5 text-sm text-slate-500">
                        Belum ada testimoni yang dipublikasikan.
                    </article>
                @endforelse
            </div>
            <div class="mt-4 flex justify-center gap-2">
                <button type="button" class="btn btn-sm testimonial-prev">Prev</button>
                <button type="button" class="btn btn-sm testimonial-next">Next</button>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const aduanLat = document.getElementById('aduan_latitude');
    const aduanLng = document.getElementById('aduan_longitude');
    const promptEl = document.getElementById('location-prompt');
    const statusEl = document.getElementById('location-status');
    const messageEl = document.getElementById('location-message');
    const actionsEl = document.getElementById('location-actions');
    const guideModal = document.getElementById('location-guide-modal');

    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
    const isFirefox = /firefox/i.test(navigator.userAgent);
    const isEdge = /edg\//i.test(navigator.userAgent);
    const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    // ─── UI Helpers ───
    function hidePrompt() {
        promptEl.classList.add('hidden');
    }

    function setLocationStatus(type, message) {
        hidePrompt();
        statusEl.classList.remove('hidden', 'border-amber-200', 'bg-amber-50', 'text-amber-700',
            'border-green-200', 'bg-green-50', 'text-green-700',
            'border-red-200', 'bg-red-50', 'text-red-700');

        const styles = {
            loading: ['border-amber-200', 'bg-amber-50', 'text-amber-700'],
            success: ['border-green-200', 'bg-green-50', 'text-green-700'],
            error:   ['border-red-200', 'bg-red-50', 'text-red-700'],
        };

        statusEl.classList.add(...(styles[type] || styles.loading));
        messageEl.textContent = message;
        actionsEl.classList.toggle('hidden', type !== 'error');
        actionsEl.style.display = type === 'error' ? 'flex' : 'none';

        if (type === 'success') {
            setTimeout(() => statusEl.classList.add('hidden'), 4000);
        }
    }

    function skipLocation() {
        hidePrompt();
    }

    // ─── Modal Panduan ───
    function detectBrowserGuide() {
        if (isMobile) return 'guide-mobile';
        if (isSafari) return 'guide-safari';
        if (isFirefox) return 'guide-firefox';
        if (isEdge) return 'guide-edge';
        return 'guide-chrome';
    }

    function showLocationGuide() {
        document.querySelectorAll('[id^="guide-"]').forEach(el => el.classList.add('hidden'));
        document.getElementById(detectBrowserGuide())?.classList.remove('hidden');
        guideModal.classList.remove('hidden');
        guideModal.classList.add('flex');
    }

    function closeLocationGuide() {
        guideModal.classList.add('hidden');
        guideModal.classList.remove('flex');
    }

    // ─── Geolocation Core ───
    function onLocationSuccess(position) {
        aduanLat.value = position.coords.latitude;
        aduanLng.value = position.coords.longitude;
        setLocationStatus('success', '✓ Lokasi berhasil terdeteksi.');
    }

    function attemptGeolocation(highAccuracy) {
        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: highAccuracy,
                timeout: 15000,
                maximumAge: 60000
            });
        });
    }

    async function requestGeolocation() {
        if (!navigator.geolocation) {
            setLocationStatus('error', 'Browser Anda tidak mendukung deteksi lokasi.');
            return;
        }

        setLocationStatus('loading', '⟳ Mendeteksi lokasi Anda...');

        // Pasang permission change listener
        if (navigator.permissions && navigator.permissions.query) {
            try {
                const perm = await navigator.permissions.query({ name: 'geolocation' });
                perm.onchange = () => {
                    if (perm.state === 'granted' || perm.state === 'prompt') {
                        requestGeolocation();
                    }
                };
            } catch (e) { /* Safari */ }
        }

        try {
            const position = await attemptGeolocation(true);
            onLocationSuccess(position);
        } catch (highAccError) {
            if (highAccError.code === 2 || highAccError.code === 3) {
                try {
                    setLocationStatus('loading', '⟳ Mencoba metode alternatif...');
                    const position = await attemptGeolocation(false);
                    onLocationSuccess(position);
                } catch (lowAccError) {
                    setLocationStatus('error', lowAccError.code === 1
                        ? 'Akses lokasi ditolak. Klik "Panduan Reset Izin" untuk mengaktifkannya.'
                        : 'Lokasi gagal terdeteksi. Klik "Coba Lagi".');
                }
            } else {
                setLocationStatus('error', highAccError.code === 1
                    ? 'Akses lokasi ditolak. Klik "Panduan Reset Izin" untuk mengaktifkannya.'
                    : 'Lokasi gagal terdeteksi. Klik "Coba Lagi".');
            }
        }
    }

    // ─── Init: Cek permission state dan tentukan UI awal ───
    document.addEventListener('DOMContentLoaded', async () => {
        if (!navigator.geolocation) return;

        if (navigator.permissions && navigator.permissions.query) {
            try {
                const perm = await navigator.permissions.query({ name: 'geolocation' });

                if (perm.state === 'granted') {
                    // Sudah pernah diizinkan → langsung ambil lokasi tanpa prompt
                    requestGeolocation();
                    return;
                }

                if (perm.state === 'denied') {
                    // Pernah ditolak → tampilkan error + panduan reset
                    setLocationStatus('error', 'Akses lokasi ditolak. Klik "Panduan Reset Izin" untuk mengaktifkannya.');
                    perm.onchange = () => {
                        if (perm.state === 'granted' || perm.state === 'prompt') {
                            requestGeolocation();
                        }
                    };
                    return;
                }

                // state === 'prompt' → Tampilkan banner agar user klik "Izinkan Lokasi"
                promptEl.classList.remove('hidden');
                return;
            } catch (e) { /* Safari */ }
        }

        // Fallback (Safari dll): tampilkan banner
        promptEl.classList.remove('hidden');
    });
</script>
@endpush