@extends('layouts.admin')

@section('title', 'Admin Galeri')
@section('page_title', 'Galeri')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.gallery-items.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah Foto</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($galleryItems as $item)
                        <tr>
                            <td>
                                <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="h-12 w-20 rounded object-cover">
                            </td>
                            <td>
                                <p class="font-semibold">{{ $item->title }}</p>
                                <p class="text-xs text-slate-500">/{{ $item->slug }}</p>
                            </td>
                            <td>{{ $item->category ?: '-' }}</td>
                            <td>{{ $item->event_date?->format('d-m-Y') ?: '-' }}</td>
                            <td><span class="badge {{ $item->is_published ? 'badge-success' : 'badge-ghost' }}">{{ $item->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.gallery-items.edit', $item) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.gallery-items.destroy', $item) }}" onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-slate-500">Belum ada item galeri.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $galleryItems->links() }}</div>
    </section>
@endsection
