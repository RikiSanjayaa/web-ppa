<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ConsultationsExport;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        $consultations = Consultation::query()
            ->filter($filters)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.consultations.index', [
            'consultations' => $consultations,
            'filters' => $filters,
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

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        return Excel::download(new ConsultationsExport($filters), 'konsultasi-'.now()->format('YmdHis').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        $consultations = Consultation::query()->filter($filters)->latest()->get();

        $pdf = Pdf::loadView('exports.consultations-pdf', [
            'consultations' => $consultations,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('konsultasi-'.now()->format('YmdHis').'.pdf');
    }
}
