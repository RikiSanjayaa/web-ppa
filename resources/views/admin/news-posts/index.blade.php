@extends('layouts.admin')

@section('title', 'Admin Berita & Event')
@section('page_title', 'Berita & Event')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.news-posts.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah Konten</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Publish</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($newsPosts as $post)
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $post->title }}</p>
                                <p class="text-xs text-slate-500">/{{ $post->slug }}</p>
                            </td>
                            <td>{{ $post->type }}</td>
                            <td>
                                <span class="badge {{ $post->is_published ? 'badge-success' : 'badge-ghost' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                            </td>
                            <td>{{ optional($post->published_at)->format('d-m-Y H:i') ?: '-' }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.news-posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.news-posts.destroy', $post) }}" onsubmit="return confirm('Hapus konten ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">Belum ada konten.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $newsPosts->links() }}</div>
    </section>
@endsection
