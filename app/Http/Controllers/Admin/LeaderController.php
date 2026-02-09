<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.leaders.index', [
            'leaders' => Leader::query()->ordered()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leaders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('leaders', 'public');
        }

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        $leader = Leader::query()->create($validated);

        ActivityLogger::log('leader.created', $leader, 'Data pimpinan dibuat.');

        return redirect()->route('admin.leaders.index')->with('status', 'Data pimpinan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.leaders.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leader $leader)
    {
        return view('admin.leaders.edit', [
            'leader' => $leader,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leader $leader)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('photo')) {
            if ($leader->photo_path) {
                Storage::disk('public')->delete($leader->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('leaders', 'public');
        }

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        $leader->update($validated);

        ActivityLogger::log('leader.updated', $leader, 'Data pimpinan diperbarui.');

        return redirect()->route('admin.leaders.index')->with('status', 'Data pimpinan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leader $leader)
    {
        if ($leader->photo_path) {
            Storage::disk('public')->delete($leader->photo_path);
        }

        $leader->delete();

        ActivityLogger::log('leader.deleted', $leader, 'Data pimpinan dihapus.');

        return redirect()->route('admin.leaders.index')->with('status', 'Data pimpinan berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
