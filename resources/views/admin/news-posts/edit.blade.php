@extends('layouts.admin')

@section('title', 'Edit Berita/Event')
@section('page_title', 'Edit Berita/Event')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.news-posts.update', $newsPost) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.news-posts._form', ['newsPost' => $newsPost])
            <div class="mt-5 flex gap-2">
                <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Update</button>
                <a href="{{ route('admin.news-posts.index') }}" class="btn border-slate-300 bg-white text-slate-700">Batal</a>
            </div>
        </form>
    </section>
@endsection
