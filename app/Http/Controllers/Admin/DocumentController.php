<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.documents.index', [
            'documents' => Document::query()->orderByDesc('year')->orderByDesc('published_at')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title']);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['published_at'] = $validated['is_published']
            ? ($validated['published_at'] ?? now())
            : null;

        $document = Document::query()->create($validated);

        ActivityLogger::log('document.created', $document, 'Dokumen informasi dibuat.');

        return redirect()->route('admin.documents.index')->with('status', 'Dokumen berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.documents.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return view('admin.documents.edit', [
            'document' => $document,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $this->validatedData($request, $document);

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? null, $validated['title'], $document);
        $validated['is_published'] = (bool) ($validated['is_published'] ?? false);
        $validated['published_at'] = $validated['is_published']
            ? ($validated['published_at'] ?? $document->published_at ?? now())
            : null;

        $document->update($validated);

        ActivityLogger::log('document.updated', $document, 'Dokumen informasi diperbarui.');

        return redirect()->route('admin.documents.index')->with('status', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        ActivityLogger::log('document.deleted', $document, 'Dokumen informasi dihapus.');

        return redirect()->route('admin.documents.index')->with('status', 'Dokumen berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Document $document = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('documents', 'slug')->ignore($document?->id),
            ],
            'number' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'digits:4'],
            'category' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'file' => [($document ? 'nullable' : 'required'), 'file', 'mimes:pdf', 'max:20480'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function uniqueSlug(?string $slug, string $title, ?Document $document = null): string
    {
        $base = Str::slug($slug ?: $title) ?: Str::random(8);
        $candidate = $base;
        $counter = 2;

        while (
            Document::query()
                ->where('slug', $candidate)
                ->when($document, fn ($query) => $query->where('id', '!=', $document->id))
                ->exists()
        ) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
