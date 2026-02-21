<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

    /**
     * Import a news post from an Instagram URL.
     */
    public function importInstagram(Request $request)
    {
        $request->validate([
            'instagram_url' => ['required', 'url', 'regex:/instagram\.com/'],
            'type' => ['required', 'in:berita,event'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $url = $request->input('instagram_url');

        // Extract the Instagram post code from URL for default title
        $postCode = null;
        if (preg_match('#instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_-]+)#', $url, $codeMatches)) {
            $postCode = $codeMatches[1];
        }

        // Try to scrape data (best-effort, may fail)
        $meta = $this->scrapeOgMeta($url);

        // Download thumbnail image if available
        $featuredImagePath = null;
        $imagePaths = [];
        if ($meta['image']) {
            try {
                $imageContent = Http::timeout(15)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    ])
                    ->get($meta['image'])->body();

                if ($imageContent && strlen($imageContent) > 1000) {
                    $extension = 'jpg';
                    $filename = 'news/ig-' . Str::random(20) . '.' . $extension;
                    Storage::disk('public')->put($filename, $imageContent);
                    $featuredImagePath = $filename;
                    $imagePaths = [$filename];
                }
            } catch (\Exception $e) {
                // Image download failed, continue without image
            }
        }

        // Title priority: admin input > scraped > default
        $title = $request->input('title') ?: ($meta['title'] ?: 'Instagram Post');
        $title = Str::limit($title, 250);
        $description = $meta['description'] ?: 'Lihat postingan selengkapnya di Instagram.';
        $slug = $this->uniqueSlug(null, $title);

        $newsPost = NewsPost::query()->create([
            'title' => $title,
            'slug' => $slug,
            'type' => $request->input('type'),
            'excerpt' => Str::limit($description, 300),
            'content' => $description,
            'featured_image_path' => $featuredImagePath,
            'image_paths' => $imagePaths,
            'is_published' => true,
            'published_at' => now(),
            'instagram_url' => $url,
            'source' => 'instagram',
        ]);

        ActivityLogger::log('news_post.created', $newsPost, 'Konten berita/event diimport dari Instagram.');

        $statusMsg = $featuredImagePath
            ? 'Berhasil import dari Instagram (dengan gambar): ' . $title
            : 'Berhasil import dari Instagram (tanpa gambar, embed akan ditampilkan): ' . $title;

        return redirect()->route('admin.news-posts.index')->with('status', $statusMsg);
    }

    /**
     * Scrape Instagram post data using multiple strategies.
     */
    private function scrapeOgMeta(string $url): array
    {
        $meta = ['title' => null, 'description' => null, 'image' => null];

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Accept-Encoding' => 'identity',
        ];

        // Strategy 1: Try Instagram embed page (most reliable for public posts)
        $embedUrl = $this->toEmbedUrl($url);
        if ($embedUrl) {
            try {
                $response = Http::timeout(15)->withHeaders($headers)->get($embedUrl);
                if ($response->ok()) {
                    $html = $response->body();
                    $this->parseEmbedPage($html, $meta);
                }
            } catch (\Exception $e) {
                // Embed page failed, try next strategy
            }
        }

        // Strategy 2: Try direct page OG tags (works when not blocked)
        if (! $meta['image']) {
            try {
                $response = Http::timeout(15)->withHeaders($headers)->get($url);
                if ($response->ok()) {
                    $html = $response->body();
                    $this->parseOgTags($html, $meta);
                }
            } catch (\Exception $e) {
                // Direct page failed
            }
        }

        // Clean up title - remove generic "Instagram" titles
        if ($meta['title'] && in_array(strtolower(trim($meta['title'])), ['instagram', 'instagram photo', 'login • instagram'])) {
            $meta['title'] = null;
        }

        // Use description as title if title is empty
        if (! $meta['title'] && $meta['description']) {
            $meta['title'] = Str::limit($meta['description'], 100);
        }

        return $meta;
    }

    /**
     * Convert Instagram post URL to embed URL.
     */
    private function toEmbedUrl(string $url): ?string
    {
        // Match /p/CODE/, /reel/CODE/, /tv/CODE/
        if (preg_match('#instagram\.com/(?:p|reel|tv)/([A-Za-z0-9_-]+)#', $url, $matches)) {
            return 'https://www.instagram.com/p/' . $matches[1] . '/embed/captioned/';
        }
        return null;
    }

    /**
     * Parse Instagram embed page for image and caption.
     */
    private function parseEmbedPage(string $html, array &$meta): void
    {
        // Extract image from the embed page - look for the main post image
        // Instagram embed pages use <img> tags with class "EmbeddedMediaImage"
        if (preg_match('/<img[^>]+class="[^"]*EmbeddedMediaImage[^"]*"[^>]+src="([^"]+)"/', $html, $matches)) {
            $meta['image'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }

        // Also try srcset or other img patterns in embed
        if (! $meta['image'] && preg_match('/<img[^>]+src="(https:\/\/[^"]*instagram[^"]*\/[^"]*\.jpg[^"]*)"/', $html, $matches)) {
            $meta['image'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }

        // Try to find image from background-image CSS
        if (! $meta['image'] && preg_match('/background-image:\s*url\(["\']?(https:\/\/[^"\')\s]+)["\']?\)/', $html, $matches)) {
            $meta['image'] = $matches[1];
        }

        // Try og:image from embed page
        if (! $meta['image'] && preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $matches)) {
            $meta['image'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }

        // Extract caption text from embed
        // The caption is usually in a div with class "Caption" or in "CaptionContent"
        if (preg_match('/<div[^>]+class="[^"]*Caption[^"]*"[^>]*>.*?<div[^>]*>(.*?)<\/div>/s', $html, $matches)) {
            $caption = strip_tags($matches[1]);
            $caption = trim(preg_replace('/\s+/', ' ', $caption));
            if ($caption && strtolower($caption) !== 'instagram') {
                $meta['description'] = $caption;
                if (! $meta['title']) {
                    $meta['title'] = Str::limit($caption, 100);
                }
            }
        }

        // Fallback: try to extract caption from any structured text
        if (! $meta['description'] && preg_match('/"caption"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $html, $matches)) {
            $caption = stripcslashes($matches[1]);
            $caption = trim(preg_replace('/\s+/', ' ', $caption));
            if ($caption) {
                $meta['description'] = $caption;
                if (! $meta['title']) {
                    $meta['title'] = Str::limit($caption, 100);
                }
            }
        }

        // Try to extract from the accessibility text of the image
        if (! $meta['description'] && preg_match('/<img[^>]+alt="([^"]{10,})"/', $html, $matches)) {
            $alt = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            if (stripos($alt, 'instagram') === false && stripos($alt, 'photo') === false) {
                $meta['description'] = $alt;
                if (! $meta['title']) {
                    $meta['title'] = Str::limit($alt, 100);
                }
            }
        }
    }

    /**
     * Parse standard OG meta tags from HTML.
     */
    private function parseOgTags(string $html, array &$meta): void
    {
        if (! $meta['title'] && preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']*)["\']/', $html, $matches)) {
            $title = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            if (strtolower(trim($title)) !== 'instagram') {
                $meta['title'] = $title;
            }
        }

        if (! $meta['description'] && preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']*)["\']/', $html, $matches)) {
            $desc = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            if (strtolower(trim($desc)) !== 'instagram') {
                $meta['description'] = $desc;
            }
        }

        if (! $meta['image'] && preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $matches)) {
            $meta['image'] = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        }
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
            'images.*' => ['image', 'max:20480'],
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
