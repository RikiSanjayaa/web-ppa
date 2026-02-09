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
