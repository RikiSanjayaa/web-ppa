@php
    $editing = isset($newsPost);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Judul *</label>
        <input type="text" name="title" value="{{ old('title', $newsPost->title ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $newsPost->slug ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Tipe *</label>
        <select name="type" class="select select-bordered w-full" required>
            <option value="berita" @selected(old('type', $newsPost->type ?? 'berita') === 'berita')>Berita</option>
            <option value="event" @selected(old('type', $newsPost->type ?? '') === 'event')>Event</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Ringkasan</label>
        <textarea name="excerpt" rows="2" class="textarea textarea-bordered w-full">{{ old('excerpt', $newsPost->excerpt ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Konten *</label>
        <textarea name="content" rows="8" class="textarea textarea-bordered w-full" required>{{ old('content', $newsPost->content ?? '') }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar unggulan</label>
        <input type="file" name="featured_image" accept="image/*" class="file-input file-input-bordered w-full">
        @if ($editing && $newsPost->featured_image_path)
            <img src="{{ Storage::url($newsPost->featured_image_path) }}" class="mt-2 h-28 w-full rounded-lg object-cover" alt="Preview">
        @endif
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal publish</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($newsPost) && $newsPost->published_at ? $newsPost->published_at->format('Y-m-d\TH:i') : '') }}" class="input input-bordered w-full">
        <label class="mt-2 inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" class="checkbox" @checked(old('is_published', $newsPost->is_published ?? false))>
            Publish
        </label>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Meta Title</label>
        <input type="text" name="meta_title" value="{{ old('meta_title', $newsPost->meta_title ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Meta Description</label>
        <textarea name="meta_description" rows="2" class="textarea textarea-bordered w-full">{{ old('meta_description', $newsPost->meta_description ?? '') }}</textarea>
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
