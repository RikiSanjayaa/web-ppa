<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ComplaintsExport;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintStatusHistory;
use App\Services\ActivityLogger;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        $complaints = Complaint::query()
            ->filter($filters)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'filters' => $filters,
            'statuses' => Complaint::availableStatuses(),
        ]);
    }

    public function create()
    {
        return view('admin.complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:16'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat' => ['required', 'string'],
            'tempat_kejadian' => ['required', 'string', 'max:255'],
            'waktu_kejadian' => ['required', 'date'],
            'kronologis_singkat' => ['required', 'string'],
            'korban' => ['required', 'string', 'max:255'],
            'terlapor' => ['nullable', 'string', 'max:255'],
            'saksi_saksi' => ['nullable', 'string'],
        ]);

        $complaint = Complaint::create(array_merge($validated, [
            'status' => Complaint::STATUS_MASUK,
            'channel' => 'dibuat oleh '.$request->user()->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        ActivityLogger::log(
            'complaint.created_by_admin',
            $complaint,
            'Aduan dibuat manual oleh admin.',
        );

        return redirect()->route('admin.complaints.index')
            ->with('status', 'Aduan berhasil dibuat.');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['statusHistories.changer']);

        return view('admin.complaints.show', [
            'complaint' => $complaint,
            'statuses' => Complaint::availableStatuses(),
        ]);
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Complaint::availableStatuses())],
            'note' => ['nullable', 'string'],
        ]);

        $oldStatus = $complaint->status;

        if ($oldStatus === $validated['status']) {
            return back()->with('status', 'Status tidak berubah.');
        }

        $complaint->update([
            'status' => $validated['status'],
        ]);

        ComplaintStatusHistory::query()->create([
            'complaint_id' => $complaint->id,
            'changed_by' => $request->user()?->id,
            'from_status' => $oldStatus,
            'to_status' => $validated['status'],
            'note' => $validated['note'] ?? null,
        ]);

        ActivityLogger::log(
            'complaint.status_updated',
            $complaint,
            'Status aduan diperbarui.',
            [
                'from' => $oldStatus,
                'to' => $validated['status'],
            ]
        );

        return back()->with('status', 'Status aduan berhasil diperbarui.');
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        return Excel::download(new ComplaintsExport($filters), 'aduan-'.now()->format('YmdHis').'.xlsx');
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'q']);

        $complaints = Complaint::query()->filter($filters)->latest()->get();

        $pdf = Pdf::loadView('exports.complaints-pdf', [
            'complaints' => $complaints,
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('aduan-'.now()->format('YmdHis').'.pdf');
    }
}
