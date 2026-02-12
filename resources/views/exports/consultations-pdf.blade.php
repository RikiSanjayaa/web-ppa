<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Konsultasi</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172a; }
            h1 { margin: 0 0 8px; font-size: 18px; }
            p { margin: 0 0 10px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #cbd5e1; padding: 6px; vertical-align: top; }
            th { background: #f1f5f9; text-align: left; }
            .small { font-size: 10px; color: #334155; }
            .badge-yes { color: #16a34a; font-weight: bold; }
            .badge-no { color: #dc2626; font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>Laporan Konsultasi PPA/PPO</h1>
        <p class="small">Digenerate: {{ $generatedAt->format('d-m-Y H:i:s') }}</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Klien</th>
                    <th>Permasalahan</th>
                    <th>Rekomendasi</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($consultations as $consultation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $consultation->nama_klien }}</td>
                        <td>{{ $consultation->permasalahan }}</td>
                        <td>{{ $consultation->rekomendasi ?? '-' }}</td>
                        <td>
                            @if($consultation->rekomendasi)
                                <span class="badge-yes">Sudah Ditanggapi</span>
                            @else
                                <span class="badge-no">Belum Ditanggapi</span>
                            @endif
                        </td>
                        <td>{{ $consultation->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data konsultasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
