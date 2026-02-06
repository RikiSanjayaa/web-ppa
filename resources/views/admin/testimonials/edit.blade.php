@extends('layouts.admin')

@section('title', 'Edit Testimoni')
@section('page_title', 'Edit Testimoni')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
            @csrf
            @method('PUT')
            @include('admin.testimonials._form', ['testimonial' => $testimonial])
            <div class="mt-5 flex gap-2">
                <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Update</button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn border-slate-300 bg-white text-slate-700">Batal</a>
            </div>
        </form>
    </section>
@endsection
