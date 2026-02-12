@props([
    'number' => '',
    'formUrl' => null,
])

@php
    $cleanNumber = preg_replace('/\D+/', '', $number);
    $waUrl = "https://wa.me/{$cleanNumber}";
@endphp

<div
    x-data="{ open: false }"
    {{ $attributes }}
>
    {{-- Floating Button --}}
    <button
        type="button"
        @click="open = true"
        class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg shadow-green-500/40 transition-all hover:scale-110 hover:shadow-xl hover:shadow-green-500/50 focus:outline-none focus:ring-4 focus:ring-green-300 lg:bottom-8 lg:right-8 lg:h-16 lg:w-16"
        aria-label="Hubungi via WhatsApp"
    >
        <svg class="h-7 w-7 lg:h-8 lg:w-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="absolute inset-0 animate-ping rounded-full bg-[#25D366] opacity-30"></span>
    </button>

    {{-- Modal Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm"
        x-cloak
    ></div>

    {{-- Modal Content --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="fixed inset-x-4 bottom-24 z-[70] mx-auto max-w-md lg:bottom-auto lg:top-1/2 lg:-translate-y-1/2"
        @click.outside="open = false"
        @keydown.escape.window="open = false"
        x-cloak
    >
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            {{-- Header --}}
            <div class="mb-5 flex items-start justify-between">
                <div>
                    <h3 class="font-heading text-xl font-bold text-slate-800">Butuh Bantuan?</h3>
                    <p class="mt-1 text-sm text-slate-500">Kami siap membantu Anda dengan aman dan mudah.</p>
                </div>
                <button @click="open = false" class="-mt-1 -mr-1 rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <p class="mb-5 text-sm leading-relaxed text-slate-600">
                Silakan pilih cara yang paling nyaman untuk Anda <br>
                 dan privasi tetap terjaga.
            </p>

            {{-- Choice Cards --}}
            <div class="grid gap-3">
                <a
                    href="{{ $formUrl ?? route('layanan-masyarakat') }}#form-aduan"
                    class="group relative overflow-hidden rounded-xl border-2 border-navy-500 bg-navy-50 p-4 shadow-md transition-all hover:bg-navy-100 hover:shadow-lg"
                >
                    <div class="absolute top-0 right-0 rounded-bl-lg bg-navy-500 px-2 py-0.5 text-[10px] font-bold text-white uppercase tracking-wider">
                        UTAMA
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-navy-600 text-white shadow-sm transition-transform group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 pt-1">
                            <h4 class="font-heading text-lg font-bold text-navy-800 group-hover:text-navy-900">Buat Pengaduan</h4>
                            <p class="mt-1 text-xs leading-relaxed text-slate-600">
                                Sampaikan laporan lengkap di sini agar bisa segera diproses petugas.
                            </p>
                            <div class="mt-2 flex items-center gap-1.5 text-[11px] font-medium text-navy-600 bg-navy-100/50 w-fit px-2 py-1 rounded-md">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proses lebih cepat & terdata rapi
                            </div>
                        </div>
                    </div>
                </a>

                <a
                    href="#"
                    @click="open = false; showConsultationModal = true; return false;"
                    class="group relative overflow-hidden rounded-xl border border-slate-200 p-4 text-left transition hover:border-blue-400 hover:bg-blue-50 hover:shadow-sm"
                >
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400 transition-colors group-hover:bg-blue-500 group-hover:text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-heading font-semibold text-slate-700 group-hover:text-blue-700">Konsultasi</h4>
                            <p class="text-xs text-slate-500">
                                Sampaikan masalah Anda secara tertulis.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

        {{-- Privacy Note --}}
        <div class="mt-5 flex items-center justify-center gap-2 rounded-lg bg-slate-50 py-2.5 px-3 text-xs text-slate-500">
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>Data dan identitas Anda aman dan dirahasiakan.</span>
        </div>
    </div>
</div>
</div>
