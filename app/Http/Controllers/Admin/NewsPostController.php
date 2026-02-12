<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NewsPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.news-posts.index', [
            'newsPosts' => NewsPost::query()->orderByDesc('published_at')->orderByDesc('created_at')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news-posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('news', 'public');
            }
            $validated['image_paths'] = $paths;
            $validated['featured_image_path'] = $paths[0] ?? null;
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title']);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['published_at'] = $validated['is_published']
            ? ($validated['published_at'] ?? now())
            : null;

        $newsPost = NewsPost::query()->create($validated);

        ActivityLogger::log('news_post.created', $newsPost, 'Konten berita/event dibuat.');

        return redirect()->route('admin.news-posts.index')->with('status', 'Berita/event berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.news-posts.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsPost $newsPost)
    {
        return view('admin.news-posts.edit', [
            'newsPost' => $newsPost,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsPost $newsPost)
    {
        $validated = $this->validatedData($request, $newsPost);

        // Handle Image Deletion
        $currentImages = $newsPost->image_paths ?? [];
        if (! $currentImages && $newsPost->featured_image_path) {
            $currentImages = [$newsPost->featured_image_path];
        }

        if ($request->has('delete_images')) {
            $deletedImages = $request->input('delete_images');
            foreach ($deletedImages as $delPath) {
                // PERBAIKAN: Gunakan is_string dan strict comparison (true)
                if (is_string($delPath) && in_array($delPath, $currentImages, true)) {
                    Storage::disk('public')->delete($delPath);
                    $currentImages = array_diff($currentImages, [$delPath]);
                }
            }
        }
        // Handle New Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Limit total to 5
                if (count($currentImages) >= 5) {
                    break;
                }
                $currentImages[] = $image->store('news', 'public');
            }
        }

        // Update Paths
        $validated['image_paths'] = array_values($currentImages);
        $validated['featured_image_path'] = $validated['image_paths'][0] ?? null;

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title'], $newsPost);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['published_at'] = $validated['is_published']
            ? ($validated['published_at'] ?? $newsPost->published_at ?? now())
            : null;

        $newsPost->update($validated);

        ActivityLogger::log('news_post.updated', $newsPost, 'Konten berita/event diperbarui.');

        return redirect()->route('admin.news-posts.index')->with('status', 'Berita/event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsPost $newsPost)
    {
        if ($newsPost->featured_image_path) {
            Storage::disk('public')->delete($newsPost->featured_image_path);
        }

        $newsPost->delete();

        ActivityLogger::log('news_post.deleted', $newsPost, 'Konten berita/event dihapus.');

        return redirect()->route('admin.news-posts.index')->with('status', 'Berita/event berhasil dihapus.');
    }

    private function validatedData(Request $request, ?NewsPost $newsPost = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('news_posts', 'slug')->ignore($newsPost?->id),
            ],
            'type' => ['required', 'in:berita,event'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ]);
    }

    private function uniqueSlug(?string $slug, string $title, ?NewsPost $newsPost = null): string
    {
        $base = Str::slug($slug ?: $title) ?: Str::random(8);
        $candidate = $base;
        $counter = 2;

        while (
            NewsPost::query()
                ->where('slug', $candidate)
                ->when($newsPost, fn ($query) => $query->where('id', '!=', $newsPost->id))
                ->exists()
        ) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
