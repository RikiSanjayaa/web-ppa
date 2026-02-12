@extends('layouts.admin')

@section('title', 'Admin Konsultasi')
@section('page_title', 'Data Konsultasi')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Klien</th>
                        <th>Permasalahan</th>
                        <th>Rekomendasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($consultations as $consultation)
                        <tr>
                            <td>#{{ $consultation->id }}</td>
                            <td class="font-medium">{{ $consultation->nama_klien }}</td>
                            <td class="max-w-xs truncate">{{ Str::limit($consultation->permasalahan, 50) }}</td>
                            <td class="max-w-xs truncate">
                                @if($consultation->rekomendasi)
                                    <span class="text-green-600">{{ Str::limit($consultation->rekomendasi, 30) }}</span>
                                @else
                                    <span class="badge border-0 bg-red-100 text-red-600">Belum ditanggapi</span>
                                @endif
                            </td>
                            <td>{{ $consultation->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.consultations.show', $consultation) }}"
                                        class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p>Belum ada data konsultasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $consultations->links() }}
        </div>
    </section>
@endsection
