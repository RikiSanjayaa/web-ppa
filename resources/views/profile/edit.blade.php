@extends('layouts.admin')

@section('title', 'Kelola Profil')
@section('page_title', 'Kelola Profil')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="card bg-white p-6 shadow-sm ring-1 ring-slate-200/50 sm:p-8 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card bg-white p-6 shadow-sm ring-1 ring-slate-200/50 sm:p-8 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

    </div>
@endsection
