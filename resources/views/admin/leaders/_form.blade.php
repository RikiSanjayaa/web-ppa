@php
    $editing = isset($leader);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Nama *</label>
        <input type="text" name="name" value="{{ old('name', $leader->name ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Jabatan *</label>
        <input type="text" name="position" value="{{ old('position', $leader->position ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Jobsdesk</label>
        <textarea name="bio" rows="4" class="textarea textarea-bordered w-full">{{ old('bio', $leader->bio ?? '') }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Foto</label>
        <input type="file" name="photo" accept="image/*" class="file-input file-input-bordered w-full">
        @if ($editing && $leader->photo_path)
            <img src="{{ Storage::url($leader->photo_path) }}" class="mt-2 h-28 w-full rounded-lg object-cover" alt="Preview">
        @endif
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', $leader->display_order ?? 0) }}" class="input input-bordered w-full">
        <label class="mt-2 inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" class="checkbox" @checked(old('is_active', $leader->is_active ?? true))>
            Aktif ditampilkan
        </label>
    </div>
</div>

@if ($errors->any())
    <div class="alert mt-4 border border-red-200 bg-red-50 text-red-700">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
