<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.gallery-items.index', [
            'galleryItems' => GalleryItem::query()->ordered()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery-items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title']);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);

        $galleryItem = GalleryItem::query()->create($validated);

        ActivityLogger::log('gallery_item.created', $galleryItem, 'Item galeri dibuat.');

        return redirect()->route('admin.gallery-items.index')->with('status', 'Item galeri berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.gallery-items.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GalleryItem $galleryItem)
    {
        return view('admin.gallery-items.edit', [
            'galleryItem' => $galleryItem,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GalleryItem $galleryItem)
    {
        $validated = $this->validatedData($request, $galleryItem);

        if ($request->hasFile('image')) {
            if ($galleryItem->image_path) {
                Storage::disk('public')->delete($galleryItem->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title'], $galleryItem);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);

        $galleryItem->update($validated);

        ActivityLogger::log('gallery_item.updated', $galleryItem, 'Item galeri diperbarui.');

        return redirect()->route('admin.gallery-items.index')->with('status', 'Item galeri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GalleryItem $galleryItem)
    {
        if ($galleryItem->image_path) {
            Storage::disk('public')->delete($galleryItem->image_path);
        }

        $galleryItem->delete();

        ActivityLogger::log('gallery_item.deleted', $galleryItem, 'Item galeri dihapus.');

        return redirect()->route('admin.gallery-items.index')->with('status', 'Item galeri berhasil dihapus.');
    }

    private function validatedData(Request $request, ?GalleryItem $galleryItem = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('gallery_items', 'slug')->ignore($galleryItem?->id),
            ],
            'category' => ['nullable', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'caption' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
            'image' => [($galleryItem ? 'nullable' : 'required'), 'image', 'max:5120'],
        ]);
    }

    private function uniqueSlug(?string $slug, string $title, ?GalleryItem $galleryItem = null): string
    {
        $base = Str::slug($slug ?: $title) ?: Str::random(8);
        $candidate = $base;
        $counter = 2;

        while (
            GalleryItem::query()
                ->where('slug', $candidate)
                ->when($galleryItem, fn ($query) => $query->where('id', '!=', $galleryItem->id))
                ->exists()
        ) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
