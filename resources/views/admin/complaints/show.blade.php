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

            <p class="mt-4 text-xs text-slate-500">Dibuat: {{ $complaint->created_at->format('d-m-Y H:i:s') }} | Channel: {{ $complaint->channel }}</p>
        </section>

        <section class="space-y-6">
            <article class="rounded-2xl bg-white p-5 shadow-sm">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Status Aduan</h2>
                <p class="mt-2 text-sm text-slate-600">Status saat ini: <span class="badge badge-outline">{{ $complaint->status }}</span></p>

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
        </section>
    </div>
@endsection
