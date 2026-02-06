@extends('layouts.admin')

@section('title', 'Tambah Berita/Event')
@section('page_title', 'Tambah Berita/Event')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.news-posts.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.news-posts._form')
            <div class="mt-5 flex gap-2">
                <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Simpan</button>
                <a href="{{ route('admin.news-posts.index') }}" class="btn border-slate-300 bg-white text-slate-700">Batal</a>
            </div>
        </form>
    </section>
@endsection
