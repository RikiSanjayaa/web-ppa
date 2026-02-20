@php
    $editing = isset($newsPost);
@endphp

@if ($editing && ($newsPost->source ?? 'manual') === 'instagram' && $newsPost->instagram_url)
    <div class="mb-4 flex items-center gap-3 rounded-xl border border-pink-200 bg-pink-50 p-3">
        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-pink-500 to-purple-600 text-white">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold text-pink-700">Diimport dari Instagram</p>
            <a href="{{ $newsPost->instagram_url }}" target="_blank" rel="noopener" class="truncate text-sm text-pink-600 underline hover:text-pink-800">{{ $newsPost->instagram_url }}</a>
        </div>
    </div>
@endif

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
        <label class="mb-1 block text-sm font-semibold text-slate-700">Gambar Galeri (Max 5)</label>
        <div class="rounded-lg border border-slate-300 p-4">
            {{-- Existing Images --}}
            @if ($editing && !empty($newsPost->image_paths))
                <div class="mb-4">
                    <p class="mb-2 text-xs font-semibold uppercase text-slate-500">Gambar Saat Ini:</p>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-5">
                        @foreach ($newsPost->image_paths as $path)
                            <div class="group relative aspect-video overflow-hidden rounded-lg bg-slate-100">
                                <img src="{{ Storage::url($path) }}" class="h-full w-full object-cover">
                                <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                                    <input type="checkbox" name="delete_images[]" value="{{ $path }}" class="peer sr-only">
                                    <span class="rounded bg-red-600 px-2 py-1 text-xs text-white peer-checked:bg-red-800">
                                        <span class="peer-checked:hidden">Hapus</span>
                                        <span class="hidden peer-checked:inline">Akan Dihapus</span>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-slate-400">* Klik gambar untuk menandai hapus.</p>
                </div>
            @elseif ($editing && $newsPost->featured_image_path)
                 {{-- Backward compatibility for single image --}}
                 <div class="mb-4">
                    <p class="mb-2 text-xs font-semibold uppercase text-slate-500">Gambar Saat Ini:</p>
                     <div class="group relative aspect-video w-48 overflow-hidden rounded-lg bg-slate-100">
                        <img src="{{ Storage::url($newsPost->featured_image_path) }}" class="h-full w-full object-cover">
                        <input type="hidden" name="keep_old_featured" value="1">
                    </div>
                 </div>
            @endif

            {{-- Upload New --}}
            <input type="file" name="images[]" multiple accept="image/*" class="file-input file-input-bordered w-full" id="imageInput" max="5">
            <div id="imagePreviewContainer" class="mt-4 grid hidden grid-cols-2 gap-3 md:grid-cols-5"></div>
        </div>
        <script>
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const dt = new DataTransfer();

            imageInput.addEventListener('change', function(event) {
                // Add new files to DataTransfer
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    // Check duplicate by name + size
                    let isDuplicate = false;
                    for (let j = 0; j < dt.files.length; j++) {
                        if (dt.files[j].name === file.name && dt.files[j].size === file.size) {
                            isDuplicate = true;
                            break;
                        }
                    }
                    if (!isDuplicate && dt.files.length < 5) {
                        dt.items.add(file);
                    }
                }

                // Update input files
                this.files = dt.files;

                // Update Preview
                updatePreview();
            });

            function updatePreview() {
                previewContainer.innerHTML = '';
                if (dt.files.length > 0) {
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }

                for (let i = 0; i < dt.files.length; i++) {
                    const file = dt.files[i];
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'group relative aspect-video overflow-hidden rounded-lg bg-slate-100 border border-slate-200';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-full w-full object-cover">
                            <button type="button" class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100" onclick="removeFile(${i})">
                                <span class="rounded bg-red-600 px-2 py-1 text-xs text-white">Hapus</span>
                            </button>
                        `;
                        previewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
            }

            window.removeFile = function(index) {
                const newDt = new DataTransfer();
                for (let i = 0; i < dt.files.length; i++) {
                    if (index !== i) {
                        newDt.items.add(dt.files[i]);
                    }
                }
                dt.items.clear();
                for (let i = 0; i < newDt.files.length; i++) {
                    dt.items.add(newDt.files[i]);
                }
                imageInput.files = dt.files;
                updatePreview();
            }
        </script>
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
