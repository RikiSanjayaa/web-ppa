@php
    $editing = isset($document);
@endphp

<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Judul *</label>
        <input type="text" name="title" value="{{ old('title', $document->title ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Slug (opsional)</label>
        <input type="text" name="slug" value="{{ old('slug', $document->slug ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Nomor Dokumen</label>
        <input type="text" name="number" value="{{ old('number', $document->number ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Tahun</label>
        <input type="number" name="year" min="1900" max="2200" value="{{ old('year', $document->year ?? '') }}" class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Kategori</label>
        <input type="text" name="category" value="{{ old('category', $document->category ?? 'Peraturan') }}" class="input input-bordered w-full">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Ringkasan</label>
        <textarea name="summary" rows="3" class="textarea textarea-bordered w-full">{{ old('summary', $document->summary ?? '') }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">File PDF {{ $editing ? '(opsional)' : '*' }}</label>
        <input type="file" name="file" accept="application/pdf" class="file-input file-input-bordered w-full">
        @if ($editing && $document->file_path)
            <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="mt-2 inline-block text-sm font-semibold text-coral-600">Lihat file saat ini</a>
        @endif
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal Publish</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($document) && $document->published_at ? $document->published_at->format('Y-m-d\TH:i') : '') }}" class="input input-bordered w-full">
        <label class="mt-2 inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" class="checkbox" @checked(old('is_published', $document->is_published ?? true))>
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
