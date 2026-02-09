@extends('layouts.admin')

@section('title', 'Admin Atasan')
@section('page_title', 'Data Atasan')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.leaders.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah
            Atasan</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaders as $leader)
                        <tr>
                            <td>
                                @if ($leader->photo_path)
                                    <div class="avatar">
                                        <div class="w-12 h-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                            <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}" />
                                        </div>
                                    </div>
                                @else
                                    <div class="avatar placeholder">
                                        <div class="bg-neutral-focus text-neutral-content rounded-full w-12">
                                            <span>{{ substr($leader->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="font-semibold">{{ $leader->name }}</td>
                            <td>{{ $leader->position }}</td>
                            <td>{{ $leader->display_order }}</td>
                            <td><span
                                    class="badge {{ $leader->is_active ? 'badge-success' : 'badge-ghost' }}">{{ $leader->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.leaders.edit', $leader) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.leaders.destroy', $leader) }}"
                                        onsubmit="return confirm('Hapus data atasan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500">Belum ada data atasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $leaders->links() }}</div>
    </section>
@endsection