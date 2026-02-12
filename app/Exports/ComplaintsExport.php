<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComplaintsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(private readonly array $filters) {}

    public function collection()
    {
        return Complaint::query()
            ->filter($this->filters)
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pelapor',
            'No HP',
            'NIK',
            'Email',
            'Alamat',
            'Tempat Kejadian',
            'Waktu Kejadian',
            'Kronologis',
            'Korban',
            'Terlapor',
            'Saksi-saksi',
            'Status',
            'Tanggal Aduan',
        ];
    }

    public function map($complaint): array
    {
        return [
            $complaint->id,
            $complaint->nama_lengkap,
            $complaint->no_hp,
            $complaint->nik,
            $complaint->email,
            $complaint->alamat,
            $complaint->tempat_kejadian,
            $complaint->waktu_kejadian?->format('Y-m-d H:i:s'),
            $complaint->kronologis_singkat,
            $complaint->korban,
            $complaint->terlapor,
            $complaint->saksi_saksi,
            $complaint->status,
            $complaint->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
