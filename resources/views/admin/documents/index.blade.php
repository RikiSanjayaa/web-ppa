@extends('layouts.admin')

@section('title', 'Admin Dokumen')
@section('page_title', 'Dokumen UU/Peraturan')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.documents.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah Dokumen</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $document->title }}</p>
                                <p class="text-xs text-slate-500">/{{ $document->slug }}</p>
                            </td>
                            <td>{{ $document->category }}</td>
                            <td>{{ $document->year ?: '-' }}</td>
                            <td><span class="badge {{ $document->is_published ? 'badge-success' : 'badge-ghost' }}">{{ $document->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate-500">Belum ada dokumen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $documents->links() }}</div>
    </section>
@endsection
