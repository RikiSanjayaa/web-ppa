@extends('layouts.admin')

@section('title', 'Edit Dokumen')
@section('page_title', 'Edit Dokumen')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.documents._form', ['document' => $document])
            <div class="mt-5 flex gap-2">
                <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Update</button>
                <a href="{{ route('admin.documents.index') }}" class="btn border-slate-300 bg-white text-slate-700">Batal</a>
            </div>
        </form>
    </section>
@endsection
