<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class FaqController extends Controller
{
  public function index()
  {
    return view('admin.faqs.index', [
      'faqs' => Faq::query()->ordered()->paginate(20),
    ]);
  }

  public function create()
  {
    return view('admin.faqs.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'question' => ['required', 'string', 'max:500'],
      'answer' => ['required', 'string'],
      'order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ]);

    $validated['is_active'] = (bool) ($validated['is_active'] ?? true);
    $validated['order'] = $validated['order'] ?? 0;

    $faq = Faq::query()->create($validated);

    ActivityLogger::log('faq.created', $faq, 'FAQ berhasil dibuat.');

    return redirect()->route('admin.faqs.index')->with('status', 'FAQ berhasil ditambahkan.');
  }

  public function edit(Faq $faq)
  {
    return view('admin.faqs.edit', [
      'faq' => $faq,
    ]);
  }

  public function update(Request $request, Faq $faq)
  {
    $validated = $request->validate([
      'question' => ['required', 'string', 'max:500'],
      'answer' => ['required', 'string'],
      'order' => ['nullable', 'integer', 'min:0'],
      'is_active' => ['nullable', 'boolean'],
    ]);

    $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
    $validated['order'] = $validated['order'] ?? 0;

    $faq->update($validated);

    ActivityLogger::log('faq.updated', $faq, 'FAQ berhasil diperbarui.');

    return redirect()->route('admin.faqs.index')->with('status', 'FAQ berhasil diperbarui.');
  }

  public function destroy(Faq $faq)
  {
    $faq->delete();

    ActivityLogger::log('faq.deleted', $faq, 'FAQ berhasil dihapus.');

    return redirect()->route('admin.faqs.index')->with('status', 'FAQ berhasil dihapus.');
  }
}
