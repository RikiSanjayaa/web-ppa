<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource with dashboard stats.
     */
    public function index(Request $request)
    {
        // --- Dashboard Stats ---
        $allTestimonials = Testimonial::all();
        $totalCount = $allTestimonials->count();
        $averageRating = $totalCount > 0 ? round($allTestimonials->avg('rating'), 1) : 0;
        $publishedCount = $allTestimonials->where('is_published', true)->count();
        $pendingCount = $allTestimonials->where('is_published', false)->count();
        $verifiedCount = $allTestimonials->where('is_verified', true)->count();

        // Distribusi bintang
        $starDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $allTestimonials->where('rating', $i)->count();
            $starDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalCount > 0 ? round(($count / $totalCount) * 100) : 0,
            ];
        }

        // --- Filtered Query ---
        $query = Testimonial::query();

        // Filter berdasarkan status
        $filter = $request->get('filter', 'semua');
        match ($filter) {
            'disetujui' => $query->where('is_published', true),
            'menunggu' => $query->where('is_published', false),
            'terverifikasi' => $query->where('is_verified', true),
            default => null,
        };

        // Pencarian
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('relation', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'terlama' => $query->orderBy('created_at', 'asc'),
            'rating_tinggi' => $query->orderByDesc('rating'),
            'rating_rendah' => $query->orderBy('rating', 'asc'),
            default => $query->orderByDesc('created_at'),
        };

        $testimonials = $query->paginate(15)->appends($request->query());

        return view('admin.testimonials.index', compact(
            'testimonials', 'totalCount', 'averageRating', 'publishedCount',
            'pendingCount', 'verifiedCount', 'starDistribution', 'filter', 'sort'
        ));
    }

    /**
     * Auto-approve testimoni dengan rating >= bintang minimum.
     */
    public function autoApprove(Request $request)
    {
        $minRating = $request->get('min_rating', 3);

        $count = Testimonial::where('is_published', false)
            ->where('rating', '>=', $minRating)
            ->update(['is_published' => true]);

        ActivityLogger::log('testimonial.auto_approved', null, "Auto-approve {$count} testimoni dengan rating >= {$minRating} bintang.");

        return redirect()->route('admin.testimonials.index')
            ->with('status', "{$count} testimoni berhasil disetujui otomatis (rating ≥ {$minRating} bintang).");
    }

    /**
     * Toggle status publish satu testimoni.
     */
    public function togglePublish(Testimonial $testimonial)
    {
        $testimonial->update(['is_published' => !$testimonial->is_published]);

        $status = $testimonial->is_published ? 'disetujui' : 'dibatalkan';
        ActivityLogger::log('testimonial.toggled', $testimonial, "Testimoni {$status} untuk tampil.");

        return redirect()->route('admin.testimonials.index')
            ->with('status', "Testimoni berhasil {$status}.");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);

        $testimonial = Testimonial::query()->create($validated);

        ActivityLogger::log('testimonial.created', $testimonial, 'Testimoni dibuat.');

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.testimonials.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $this->validatedData($request);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);

        $testimonial->update($validated);

        ActivityLogger::log('testimonial.updated', $testimonial, 'Testimoni diperbarui.');

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        ActivityLogger::log('testimonial.deleted', $testimonial, 'Testimoni dihapus.');

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'relation' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }
}
