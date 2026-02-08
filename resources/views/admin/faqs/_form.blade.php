<div class="grid gap-4">
  <div class="form-control">
    <label class="label">
      <span class="label-text font-medium">Pertanyaan <span class="text-red-500">*</span></span>
    </label>
    <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}" class="input input-bordered"
      required>
    @error('question')
      <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
  </div>

  <div class="form-control">
    <label class="label">
      <span class="label-text font-medium">Jawaban <span class="text-red-500">*</span></span>
    </label>
    <textarea name="answer" rows="5" class="textarea textarea-bordered"
      required>{{ old('answer', $faq->answer ?? '') }}</textarea>
    @error('answer')
      <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
  </div>

  <div class="grid gap-4 md:grid-cols-2">
    <div class="form-control">
      <label class="label">
        <span class="label-text font-medium">Urutan</span>
      </label>
      <input type="number" name="order" value="{{ old('order', $faq->order ?? 0) }}" class="input input-bordered"
        min="0">
      <p class="mt-1 text-xs text-slate-500">Angka lebih kecil ditampilkan lebih dulu.</p>
    </div>

    <div class="form-control">
      <label class="label cursor-pointer justify-start gap-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="checkbox" {{ old('is_active', $faq->is_active ?? true) ? 'checked' : '' }}>
        <span class="label-text font-medium">Aktif</span>
      </label>
    </div>
  </div>
</div>