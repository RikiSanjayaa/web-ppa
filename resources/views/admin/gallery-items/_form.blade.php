@php
    $editing = isset($galleryItem);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Judul *</label>
        <input type="text" name="title" value="{{ old('title', $galleryItem->title ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $galleryItem->slug ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Kategori</label>
        <input type="text" name="category" value="{{ old('category', $galleryItem->category ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal Kegiatan</label>
        <input type="date" name="event_date" value="{{ old('event_date', isset($galleryItem) && $galleryItem->event_date ? $galleryItem->event_date->format('Y-m-d') : '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', $galleryItem->display_order ?? 0) }}" class="input input-bordered w-full">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Caption</label>
        <textarea name="caption" rows="3" class="textarea textarea-bordered w-full">{{ old('caption', $galleryItem->caption ?? '') }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Foto {{ $editing ? '(opsional)' : '*' }}</label>
        <input type="file" name="image" accept="image/*" class="file-input file-input-bordered w-full">
        @if ($editing && $galleryItem->image_path)
            <img src="{{ Storage::url($galleryItem->image_path) }}" class="mt-2 h-28 w-full rounded-lg object-cover" alt="Preview">
        @endif
    </div>
    <div>
        <label class="mt-8 inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" class="checkbox" @checked(old('is_published', $galleryItem->is_published ?? true))>
            Publish
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
