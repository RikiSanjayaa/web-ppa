@extends('layouts.public')

@section('title', 'Konsultasi Berhasil Dikirim | ' . $settings['site_name'])

@section('content')
    <section class="min-h-[60vh] flex items-center justify-center p-6" data-aos="fade-up">
        <div class="max-w-md w-full text-center">
            <div class="mb-6 flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 animate-ping rounded-full bg-green-100 opacity-75"></div>
                    <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-green-50 text-green-600">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <h1 class="font-heading text-2xl font-bold text-navy-700">Konsultasi Berhasil Disimpan!</h1>
            <p class="mt-3 text-slate-600">
                Terima kasih, <strong>{{ $consultation->nama_klien }}</strong>. Data konsultasi Anda telah tersimpan di sistem kami.
            </p>

            <div class="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <p class="mb-4 text-sm text-blue-800">
                    Sistem akan mengarahkan Anda ke WhatsApp admin dalam <span id="countdown" class="font-bold">3</span> detik...
                </p>
                
                <a href="{{ $waUrl }}" id="wa-link"
                    class="btn w-full justify-center border-0 bg-green-600 text-white hover:bg-green-700 shadow-lg shadow-green-600/20">
                    <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    Buka WhatsApp Sekarang
                </a>
            </div>
            
            <p class="mt-6 text-sm text-slate-500">
                Jika WhatsApp tidak terbuka otomatis, silakan klik tombol di atas.
            </p>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 3;
            const countdownEl = document.getElementById('countdown');
            const waLink = document.getElementById('wa-link').href;
            
            // Decode HTML entities in URL just in case
            const decodedUrl = waLink.replace(/&amp;/g, '&');

            const timer = setInterval(function() {
                countdown--;
                countdownEl.innerText = countdown;

                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = decodedUrl;
                }
            }, 1000);
        });
    </script>
@endsection
