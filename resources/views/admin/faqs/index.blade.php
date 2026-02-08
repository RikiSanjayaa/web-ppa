@extends('layouts.admin')

@section('title', 'Admin FAQ')
@section('page_title', 'FAQ (Pertanyaan Umum)')

@section('content')
  <div class="mb-4 flex justify-end">
    <a href="{{ route('admin.faqs.create') }}" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Tambah
      FAQ</a>
  </div>

  <section class="rounded-2xl bg-white p-5 shadow-sm">
    <div class="overflow-x-auto">
      <table class="table table-zebra">
        <thead>
          <tr>
            <th>Urutan</th>
            <th>Pertanyaan</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($faqs as $faq)
            <tr>
              <td class="w-16 text-center">{{ $faq->order }}</td>
              <td>
                <p class="font-semibold">{{ Str::limit($faq->question, 80) }}</p>
                <p class="text-xs text-slate-500 line-clamp-1">{{ Str::limit($faq->answer, 100) }}</p>
              </td>
              <td>
                <span
                  class="badge {{ $faq->is_active ? 'badge-success' : 'badge-ghost' }}">{{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}</span>
              </td>
              <td>
                <div class="flex items-center gap-2">
                  <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm">Edit</a>
                  <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                    onsubmit="return confirm('Hapus FAQ ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm border-0 bg-red-500 text-white hover:bg-red-600">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-slate-500">Belum ada FAQ.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-4">{{ $faqs->links() }}</div>
  </section>
@endsection