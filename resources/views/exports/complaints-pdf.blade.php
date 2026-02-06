<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <title>Laporan Aduan</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172a; }
            h1 { margin: 0 0 8px; font-size: 18px; }
            p { margin: 0 0 10px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #cbd5e1; padding: 6px; vertical-align: top; }
            th { background: #f1f5f9; text-align: left; }
            .small { font-size: 10px; color: #334155; }
        </style>
    </head>
    <body>
        <h1>Laporan Aduan PPA/PPO</h1>
        <p class="small">Digenerate: {{ $generatedAt->format('d-m-Y H:i:s') }}</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelapor</th>
                    <th>Kontak</th>
                    <th>Lokasi/Waktu</th>
                    <th>Kronologis</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($complaints as $complaint)
                    <tr>
                        <td>#{{ $complaint->id }}</td>
                        <td>
                            {{ $complaint->nama_lengkap }}<br>
                            NIK: {{ $complaint->nik ?: '-' }}
                        </td>
                        <td>
                            HP: {{ $complaint->no_hp }}<br>
                            Email: {{ $complaint->email ?: '-' }}
                        </td>
                        <td>
                            {{ $complaint->tempat_kejadian }}<br>
                            {{ $complaint->waktu_kejadian?->format('d-m-Y H:i') }}
                        </td>
                        <td>{{ $complaint->kronologis_singkat }}</td>
                        <td>{{ $complaint->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Tidak ada data aduan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
