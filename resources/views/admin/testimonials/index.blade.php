@extends('layouts.admin')

@section('title', 'Admin Testimoni')
@section('page_title', 'Testimoni')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.testimonials.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah Testimoni</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Relasi</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($testimonials as $testimonial)
                        <tr>
                            <td class="font-semibold">{{ $testimonial->name }}</td>
                            <td>{{ $testimonial->relation ?: '-' }}</td>
                            <td>{{ str_repeat('★', max(1, min(5, $testimonial->rating))) }}</td>
                            <td><span class="badge {{ $testimonial->is_published ? 'badge-success' : 'badge-ghost' }}">{{ $testimonial->is_published ? 'Published' : 'Draft' }}</span></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-slate-500">Belum ada testimoni.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $testimonials->links() }}</div>
    </section>
@endsection
