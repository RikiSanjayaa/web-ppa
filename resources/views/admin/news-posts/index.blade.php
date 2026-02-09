@extends('layouts.admin')

@section('title', 'Admin Berita & Event')
@section('page_title', 'Berita & Event')

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.news-posts.create') }}"
            class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah Konten</a>
    </div>

    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Media</th>
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
                                @if ($post->featured_image_path)
                                    <div class="avatar">
                                        <div class="h-12 w-20 rounded-md overflow-hidden flex justify-center bg-neutral-100">
                                            <img src="{{ Storage::url($post->featured_image_path) }}" alt="{{ $post->title }}"
                                                class="h-full w-auto object-contain" />
                                        </div>
                                    </div>
                                @else
                                    <div class="h-12 w-20 bg-slate-100 rounded-md flex items-center justify-center text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <p class="font-semibold">{{ $post->title }}</p>
                                <p class="text-xs text-slate-500">/{{ $post->slug }}</p>
                            </td>
                            <td>{{ $post->type }}</td>
                            <td>
                                <span
                                    class="badge {{ $post->is_published ? 'badge-success' : 'badge-ghost' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span>
                            </td>
                            <td>{{ optional($post->published_at)->format('d-m-Y H:i') ?: '-' }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.news-posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.news-posts.destroy', $post) }}"
                                        onsubmit="return confirm('Hapus konten ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
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