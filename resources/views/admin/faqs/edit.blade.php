@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page_title', 'Edit FAQ')

@section('content')
  <section class="rounded-2xl bg-white p-5 shadow-sm">
    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
      @csrf
      @method('PUT')
      @include('admin.faqs._form', ['faq' => $faq])
      <div class="mt-5 flex gap-2">
        <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Simpan Perubahan</button>
        <a href="{{ route('admin.faqs.index') }}" class="btn border-slate-300 bg-white text-slate-700">Batal</a>
      </div>
    </form>
  </section>
@endsection