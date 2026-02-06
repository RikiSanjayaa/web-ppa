<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Nama *</label>
        <input type="text" name="name" value="{{ old('name', $testimonial->name ?? '') }}" required class="input input-bordered w-full">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Relasi</label>
        <input type="text" name="relation" value="{{ old('relation', $testimonial->relation ?? '') }}" class="input input-bordered w-full" placeholder="Masyarakat / Korban / Orang tua">
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Rating *</label>
        <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating ?? 5) }}" class="input input-bordered w-full" required>
    </div>
    <div>
        <label class="mb-1 block text-sm font-semibold text-slate-700">Urutan Tampil</label>
        <input type="number" name="display_order" min="0" value="{{ old('display_order', $testimonial->display_order ?? 0) }}" class="input input-bordered w-full">
        <label class="mt-2 inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" class="checkbox" @checked(old('is_published', $testimonial->is_published ?? true))>
            Publish
        </label>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-semibold text-slate-700">Isi Testimoni *</label>
        <textarea name="content" rows="5" class="textarea textarea-bordered w-full" required>{{ old('content', $testimonial->content ?? '') }}</textarea>
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
