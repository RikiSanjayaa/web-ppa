<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $consultations = Consultation::query()
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.consultations.index', [
            'consultations' => $consultations,
        ]);
    }

    public function show(Consultation $consultation)
    {
        return view('admin.consultations.show', [
            'consultation' => $consultation,
        ]);
    }

    public function edit(Consultation $consultation)
    {
        return view('admin.consultations.edit', [
            'consultation' => $consultation,
        ]);
    }

    public function update(Request $request, Consultation $consultation)
    {
        $validated = $request->validate([
            'rekomendasi' => 'required|string',
        ]);

        $consultation->update($validated);

        return back()->with('success', 'Rekomendasi berhasil diperbarui.');
    }
}
