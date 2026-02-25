@extends('layouts.admin')

@section('title', 'Tambah Dokumen')
@section('page_title', 'Tambah Dokumen')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm" x-data="{ isSubmitting: false }">
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" @submit="isSubmitting = true">
            @csrf
            @include('admin.documents._form')
            <div class="mt-5 flex gap-2">
                <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800" x-bind:disabled="isSubmitting">
                    <span x-show="!isSubmitting">Simpan</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2" x-cloak>
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
                <a href="{{ route('admin.documents.index') }}" class="btn border-slate-300 bg-white text-slate-700" x-bind:class="{ 'pointer-events-none opacity-50': isSubmitting }">Batal</a>
            </div>
        </form>
    </section>
@endsection
