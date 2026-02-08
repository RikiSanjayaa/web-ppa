<?php

namespace App\Http\Controllers;

use App\Models\HotlineAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotlineAccessController extends Controller
{
  public function store(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'latitude' => ['nullable', 'numeric', 'between:-90,90'],
      'longitude' => ['nullable', 'numeric', 'between:-180,180'],
      'accuracy' => ['nullable', 'numeric', 'min:0'],
      'location_error' => ['nullable', 'string', 'max:255'],
    ]);

    HotlineAccess::create([
      'ip_address' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'referrer' => $request->header('Referer'),
      'latitude' => $validated['latitude'] ?? null,
      'longitude' => $validated['longitude'] ?? null,
      'accuracy' => $validated['accuracy'] ?? null,
      'location_error' => $validated['location_error'] ?? null,
      'clicked_at' => now(),
    ]);

    return response()->json(['success' => true]);
  }
}
