@extends('layouts.admin')

@section('title', 'Buat Aduan Baru')
@section('page_title', 'Buat Aduan Manual')

@section('content')
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-6">
      <h2 class="text-xl font-heading font-semibold text-navy-700 mb-6">Formulir Aduan Manual</h2>
      <p class="text-sm text-slate-600 mb-6">Gunakan form ini untuk mencatat aduan yang masuk melalui jalur lain
        (telepon/langsung) secara manual.</p>

      @if ($errors->any())
        <div class="alert alert-error mb-6 bg-red-50 text-red-700 border-red-200">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.complaints.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Data Pelapor -->
          <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-4">Data Pelapor</h3>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Nama Lengkap *</span></label>
            <input type="text" name="nama_lengkap" class="input input-bordered w-full" value="{{ old('nama_lengkap') }}"
              required>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Nomor HP *</span></label>
            <input type="text" name="no_hp" class="input input-bordered w-full" value="{{ old('no_hp') }}" required>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">NIK *</span></label>
            <input type="text" name="nik" class="input input-bordered w-full" value="{{ old('nik') }}" maxlength="16"
              required>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Email (Opsional)</span></label>
            <input type="email" name="email" class="input input-bordered w-full" value="{{ old('email') }}">
          </div>

          <div class="form-control md:col-span-2">
            <label class="label"><span class="label-text font-semibold">Alamat *</span></label>
            <textarea name="alamat" class="textarea textarea-bordered w-full h-24" placeholder="Alamat lengkap pelapor..."
              required>{{ old('alamat') }}</textarea>
          </div>

          <!-- Data Kejadian -->
          <div class="md:col-span-2 mt-4">
            <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-4">Data Kejadian</h3>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Tempat Kejadian *</span></label>
            <input type="text" name="tempat_kejadian" class="input input-bordered w-full"
              value="{{ old('tempat_kejadian') }}" required>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Waktu Kejadian *</span></label>
            <input type="date" name="waktu_kejadian" class="input input-bordered w-full"
              value="{{ old('waktu_kejadian') }}" required>
          </div>

          <div class="form-control md:col-span-2">
            <label class="label"><span class="label-text font-semibold">Kronologis Singkat *</span></label>
            <textarea name="kronologis_singkat" class="textarea textarea-bordered w-full h-32"
              placeholder="Ceritakan kronologis kejadian secara singkat..."
              required>{{ old('kronologis_singkat') }}</textarea>
          </div>

          <!-- Pihak Terkait -->
          <div class="md:col-span-2 mt-4">
            <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-4">Pihak Terkait</h3>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Identitas Korban *</span></label>
            <input type="text" name="korban" class="input input-bordered w-full" placeholder="Nama/Inisial Korban"
              value="{{ old('korban') }}" required>
          </div>

          <div class="form-control">
            <label class="label"><span class="label-text font-semibold">Identitas Terlapor (Opsional)</span></label>
            <input type="text" name="terlapor" class="input input-bordered w-full" placeholder="Nama/Inisial Terlapor"
              value="{{ old('terlapor') }}">
          </div>

          <div class="form-control md:col-span-2">
            <label class="label"><span class="label-text font-semibold">Saksi-saksi (Opsional)</span></label>
            <textarea name="saksi_saksi" class="textarea textarea-bordered w-full h-24"
              placeholder="Sebutkan saksi-saksi jika ada...">{{ old('saksi_saksi') }}</textarea>
          </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
          <a href="{{ route('admin.complaints.index') }}" class="btn btn-ghost">Batal</a>
          <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800">Simpan Aduan</button>
        </div>
      </form>
    </div>
  </div>
@endsection