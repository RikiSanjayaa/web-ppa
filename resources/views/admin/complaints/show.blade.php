@extends('layouts.admin')

@section('title', 'Detail Aduan #'.$complaint->id)
@section('page_title', 'Detail Aduan #'.$complaint->id)

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-2xl bg-white p-5 shadow-sm lg:col-span-2">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Informasi Pelapor</h2>
            <dl class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                <div><dt class="text-slate-500">Nama</dt><dd class="font-semibold">{{ $complaint->nama_lengkap }}</dd></div>
                <div><dt class="text-slate-500">No HP</dt><dd class="font-semibold">{{ $complaint->no_hp }}</dd></div>
                <div><dt class="text-slate-500">NIK</dt><dd>{{ $complaint->nik ?: '-' }}</dd></div>
                <div><dt class="text-slate-500">Email</dt><dd>{{ $complaint->email ?: '-' }}</dd></div>
                <div class="md:col-span-2"><dt class="text-slate-500">Alamat</dt><dd>{{ $complaint->alamat ?: '-' }}</dd></div>
            </dl>

            <h2 class="mt-6 font-heading text-lg font-semibold text-navy-700">Data Kejadian</h2>
            <dl class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                <div><dt class="text-slate-500">Tempat</dt><dd>{{ $complaint->tempat_kejadian }}</dd></div>
                <div><dt class="text-slate-500">Waktu</dt><dd>{{ $complaint->waktu_kejadian?->format('d-m-Y H:i') }}</dd></div>
                <div><dt class="text-slate-500">Korban</dt><dd>{{ $complaint->korban ?: '-' }}</dd></div>
                <div><dt class="text-slate-500">Terlapor</dt><dd>{{ $complaint->terlapor ?: '-' }}</dd></div>
                <div class="md:col-span-2"><dt class="text-slate-500">Saksi-saksi</dt><dd>{{ $complaint->saksi_saksi ?: '-' }}</dd></div>
                <div class="md:col-span-2"><dt class="text-slate-500">Kronologis</dt><dd class="whitespace-pre-line">{{ $complaint->kronologis_singkat }}</dd></div>
            </dl>

            @if($complaint->latitude && $complaint->longitude)
                <a href="{{ route('admin.location-monitoring.index', ['lat' => $complaint->latitude, 'lng' => $complaint->longitude]) }}" 
                   class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-indigo-500 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lihat Lokasi di Peta
                </a>
            @endif

            <p class="mt-4 text-xs text-slate-500">Dibuat: {{ $complaint->created_at->format('d-m-Y H:i:s') }} | Channel: {{ $complaint->channel }}</p>
        </section>

        <section class="space-y-6">
            <article class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Status Pengaduan</h2>
                <p class="mt-2 text-sm text-slate-600">Status saat ini:
                    <x-complaint-status-badge :status="$complaint->status" />
                </p>

                <form method="POST" action="{{ route('admin.complaints.update-status', $complaint) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Ubah Status</label>
                        <select name="status" class="select select-bordered w-full" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($status === $complaint->status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Catatan (opsional)</label>
                        <textarea name="note" rows="3" class="textarea textarea-bordered w-full"></textarea>
                    </div>
                    <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Simpan Status</button>
                </form>

                @if($complaint->status === \App\Models\Complaint::STATUS_TAHAP_1)
                <div class="mt-6 border-t border-slate-100 pt-4">
                    <h3 class="font-semibold text-slate-700">Undang Testimoni</h3>
                    <p class="text-xs text-slate-500 mb-3">Generate link unik untuk pelapor mengisi testimoni.</p>
                    
                    @if($complaint->testimonial_token)
                        <div class="bg-slate-50 p-3 rounded-lg border border-slate-200">
                            <p class="text-xs font-semibold text-slate-500 mb-1">Link Testimoni:</p>
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ route('testimonials.form', $complaint->testimonial_token) }}" 
                                    class="w-full text-xs p-2 bg-white border border-slate-300 rounded select-all focus:outline-none">
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $complaint->no_hp) }}?text={{ urlencode('Halo ' . $complaint->nama_lengkap . ', terima kasih telah menggunakan layanan kami. Kami sangat menghargai privasi Anda. Jika berkenan, mohon berikan ulasan singkat melalui link aman berikut (nama Anda akan disamarkan): ' . route('testimonials.form', $complaint->testimonial_token)) }}" 
                                   target="_blank" class="btn btn-sm bg-green-500 text-white hover:bg-green-600 border-0">
                                    WA
                                </a>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('admin.complaints.generate-token', $complaint) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm w-full border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                                Generate Link Testimoni
                            </button>
                        </form>
                    @endif
                </div>
                @endif
            </article>

            <article class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Riwayat Status</h2>
                <div class="mt-3 space-y-3">
                    @forelse ($complaint->statusHistories()->latest()->get() as $history)
                        <div class="rounded-xl border border-slate-200 p-3 text-sm">
                            <p class="font-semibold text-slate-700">{{ $history->from_status ?: '-' }} → {{ $history->to_status }}</p>
                            <p class="text-xs text-slate-500">{{ $history->created_at->format('d-m-Y H:i') }} @if($history->changer) • {{ $history->changer->name }} @endif</p>
                            @if ($history->note)
                                <p class="mt-1 text-slate-600">{{ $history->note }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada riwayat status.</p>
                    @endforelse
                </div>
            </article>

            <a href="{{ route('admin.complaints.index') }}" class="btn btn-ghost w-full justify-start text-slate-600 hover:text-navy-700 hover:bg-slate-50 px-0">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar
            </a>
        </section>
    </div>
@endsection
