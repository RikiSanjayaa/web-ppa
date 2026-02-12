<?php

namespace App\Exports;

use App\Models\Consultation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConsultationsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(private readonly array $filters) {}

    public function collection()
    {
        return Consultation::query()
            ->filter($this->filters)
            ->latest()
            ->get();
    }

    private int $row = 0;

    public function headings(): array
    {
        return [
            'No',
            'Nama Klien',
            'Permasalahan',
            'Rekomendasi',
            'Status',
            'Tanggal Konsultasi',
        ];
    }

    public function map($consultation): array
    {
        return [
            ++$this->row,
            $consultation->nama_klien,
            $consultation->permasalahan,
            $consultation->rekomendasi ?? '-',
            $consultation->rekomendasi ? 'Sudah Ditanggapi' : 'Belum Ditanggapi',
            $consultation->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
