@extends('layouts.public')

@section('title', 'Tulis Testimoni | ' . $settings['site_name'])

@section('content')
<div class="flex items-center justify-center min-h-[60vh] px-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-100 p-8" data-aos="fade-up">
        <div class="mb-8">
            <h1 class="font-heading text-2xl font-bold text-navy-700">Tulis Testimoni Anda</h1>
            <p class="mt-2 text-slate-600">Hai, <span class="font-semibold text-navy-600">{{ $client_name }}</span>! Terima kasih telah menggunakan layanan kami.</p>
        </div>

        <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 flex gap-3 text-sm text-blue-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <div>
                <p class="font-semibold">Privasi Anda Terjaga Aman</p>
                <p class="mt-1 opacity-90">Nama Anda akan otomatis disamarkan (Contoh: Budi Susanto &rarr; B*** S******) saat ditampilkan. Nomor HP dan identitas kasus tidak akan dipublikasikan.</p>
            </div>
        </div>

        <form action="{{ route('testimonials.store-token', $token) }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Beri Rating Layanan Kami</label>
                <div class="flex gap-4" x-data="{ rating: 5 }">
                    <template x-for="star in 5">
                        <button type="button" @click="rating = star" class="text-3xl transition-transform hover:scale-110 focus:outline-none"
                            :class="star <= rating ? 'text-amber-400' : 'text-slate-300'">
                            ★
                        </button>
                    </template>
                    <input type="hidden" name="rating" x-model="rating">
                </div>
            </div>

            <div>
                <label for="relation" class="block text-sm font-semibold text-slate-700 mb-2">Peran Anda (Opsional)</label>
                <input type="text" id="relation" name="relation" 
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-700 focus:border-navy-600 focus:outline-none transition-colors"
                    placeholder="Contoh: Warga Pelapor / Korban / Keluarga">
            </div>

            <div>
                <label for="content" class="block text-sm font-semibold text-slate-700 mb-2">Ulasan Anda</label>
                <textarea id="content" name="content" required rows="4"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-700 focus:border-navy-600 focus:outline-none transition-colors"
                    placeholder="Ceritakan pengalaman Anda menggunakan layanan kami..."></textarea>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800 py-3 px-8 rounded-xl font-semibold shadow-lg shadow-navy-700/20 transition-all hover:shadow-navy-700/40">
                    Kirim Testimoni
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
