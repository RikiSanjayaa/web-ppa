<?php

namespace App\Http\Controllers\Admin;

use App\Models\HotlineAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HotlineAccessController
{
    public function index(Request $request): View
    {
        $query = HotlineAccess::query()->recent();

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('clicked_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('clicked_at', '<=', $request->date_to);
        }

        // Filter by location availability
        if ($request->filled('has_location')) {
            if ($request->has_location === 'yes') {
                $query->whereNotNull('latitude');
            } else {
                $query->whereNull('latitude');
            }
        }

        $accesses = $query->paginate(25)->withQueryString();

        return view('admin.hotline-accesses.index', [
            'accesses' => $accesses,
            'filters' => $request->only(['date_from', 'date_to', 'has_location']),
        ]);
    }

    public function show(HotlineAccess $hotlineAccess): View
    {
        return view('admin.hotline-accesses.show', [
            'access' => $hotlineAccess,
        ]);
    }
}
