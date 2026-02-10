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
                pencatatan resmi, gunakan form aduan di bawah.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['hotline_wa_number']) }}" target="_blank"
                    rel="noopener" data-hotline-track
                    class="btn border-0 bg-coral-500 text-white hover:bg-coral-600">Hotline WhatsApp</a>
                <a href="tel:{{ preg_replace('/\D+/', '', $settings['contact_phone']) }}"
                    class="btn border-slate-300 bg-white text-navy-700 hover:bg-slate-50">{{ $settings['contact_phone'] }}</a>
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
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Form Aduan</h2>
        <p class="mt-2 text-sm text-slate-600">Data aduan akan tersimpan di sistem, lalu Anda diarahkan ke WhatsApp hotline
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
                    placeholder="Contoh: 081234567890">
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
                <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Kirim Aduan &
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