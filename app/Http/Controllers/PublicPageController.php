<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Document;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Leader;
use App\Models\NewsPost;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Support\SiteDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicPageController extends Controller
{
    public function home(): View
    {
        $settings = $this->settings();

        return view('public.home', [
            'settings' => $settings,
            'newsPosts' => NewsPost::query()->latestPublished()->take(6)->get(),
            'leaders' => Leader::query()->active()->ordered()->take(6)->get(),
            'testimonials' => Testimonial::query()->published()->ordered()->take(8)->get(),

            'stats' => [
                'total_aduan' => Complaint::query()->count(),
                'aduan_selesai' => Complaint::query()->where('status', Complaint::STATUS_TAHAP_1)->count(),
                'total_dokumen' => Document::query()->published()->count(),
            ],
        ]);
    }

    public function organisasi(): View
    {
        return view('public.organisasi', [
            'settings' => $this->settings(),
            'leaders' => Leader::query()->active()->ordered()->get(),
        ]);
    }

    public function layananMasyarakat(): View
    {
        return view('public.layanan-masyarakat', [
            'settings' => $this->settings(),
            'testimonials' => Testimonial::query()->published()->ordered()->get(),
        ]);
    }

    public function informasiIndex(Request $request): View
    {
        $category = $request->query('category');
        $search = $request->query('q');

        $documentsQuery = Document::query()->published()->orderByDesc('year')->orderByDesc('published_at');

        if ($category) {
            $documentsQuery->where('category', $category);
        }

        if ($search) {
            $documentsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('summary', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('number', 'like', '%' . $search . '%');
            });
        }

        return view('public.informasi-index', [
            'settings' => $this->settings(),
            'documents' => $documentsQuery->paginate(10)->withQueryString(),
            'categories' => Document::query()->published()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category'),
            'faqs' => Faq::query()->active()->ordered()->get(),
            'selectedCategory' => $category,
        ]);
    }

    public function informasiShow(string $slug): View
    {
        $document = Document::query()->published()->where('slug', $slug)->first();

        if ($document) {
            return view('public.informasi-show', [
                'settings' => $this->settings(),
                'item' => $document,
                'type' => 'document',
            ]);
        }

        $news = NewsPost::query()->published()->where('slug', $slug)->firstOrFail();

        return view('public.informasi-show', [
            'settings' => $this->settings(),
            'item' => $news,
            'type' => 'news',
        ]);
    }

    public function galeri(Request $request): View
    {
        $query = NewsPost::query()->published()->orderByDesc('published_at');

        $type = $request->query('type');
        $year = $request->query('year');

        if ($type) {
            $query->where('type', $type);
        }

        if ($year) {
            $query->whereYear('published_at', $year);
        }

        $driver = DB::connection()->getDriverName();
        $yearExpression = $driver === 'sqlite'
            ? "CAST(strftime('%Y', published_at) AS INTEGER)"
            : 'YEAR(published_at)';

        // Get years from NewsPost
        $years = NewsPost::query()
                ->published()
                ->whereNotNull('published_at')
                ->selectRaw("$yearExpression as year")
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year');

        return view('public.galeri', [
            'settings' => $this->settings(),
            'newsPosts' => $query->paginate(12)->withQueryString(),
            'types' => ['berita' => 'Berita', 'event' => 'Event'],
            'years' => $years,
            'selectedType' => $type,
            'selectedYear' => $year,
        ]);
    }

    public function downloadDocument(Document $document): StreamedResponse
    {
        abort_if(!$document->is_published, 404);

        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION) ?: 'pdf';
        $filename = Str::slug($document->title) . '.' . $extension;

        return Storage::disk('public')->download($document->file_path, $filename);
    }

    /**
     * @return array<string, string>
     */
    private function settings(): array
    {
        $defaults = SiteDefaults::values();
        $stored = SiteSetting::getMap(array_keys($defaults))->all();

        return array_replace($defaults, array_filter($stored, fn(?string $value) => $value !== null));
    }
}
