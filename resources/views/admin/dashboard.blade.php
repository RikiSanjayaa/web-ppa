@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Pengaduan</p>
            <p class="mt-2 text-3xl font-bold text-navy-700">{{ number_format($totalComplaints) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Total Konsultasi</p>
            <p class="mt-2 text-3xl font-bold text-blue-600">{{ number_format($totalConsultations) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Pengaduan 7 Hari</p>
            <p class="mt-2 text-3xl font-bold text-coral-600">{{ number_format($kpis['aduan_7_hari']) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Rasio Selesai</p>
            <p class="mt-2 text-3xl font-bold text-teal-600">{{ number_format($kpis['rasio_selesai'], 1) }}%</p>
        </article>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Lokasi Pengaduan Terbanyak</h2>
                <span class="text-xs text-slate-500">per Kabupaten/Kota NTB</span>
            </div>
            <div class="h-80">
                <canvas id="topLocationsChart"></canvas>
            </div>
        </article>

        <article class="rounded-2xl bg-white p-5 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Distribusi Status Aduan</h2>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                    Live
                </span>
            </div>

            <div class="flex items-center gap-6">
                {{-- Doughnut Chart --}}
                <div class="relative flex-shrink-0" style="width: 220px; height: 220px;">
                    <canvas id="complaintStatusChart"></canvas>
                </div>

                {{-- Custom Legend dengan angka --}}
                <div class="flex-1 space-y-2.5">
                    @php
                        $statusInfo = [
                            ['label' => 'Masuk',           'color' => '#ef4444', 'bg' => 'bg-red-50',    'text' => 'text-red-600',    'ring' => 'ring-red-100'],
                            ['label' => 'LP',              'color' => '#f59e0b', 'bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'ring' => 'ring-amber-100'],
                            ['label' => 'Lidik',           'color' => '#ea580c', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'ring' => 'ring-orange-100'],
                            ['label' => 'Sidik',           'color' => '#c2410c', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'ring' => 'ring-orange-100'],
                            ['label' => 'Selesai Tahap 1', 'color' => '#14b8a6', 'bg' => 'bg-teal-50',   'text' => 'text-teal-600',   'ring' => 'ring-teal-100'],
                        ];
                        $statusDataValues = $charts['complaintStatus']['data'];
                        $totalStatus = array_sum($statusDataValues);
                    @endphp
                    @foreach($statusInfo as $idx => $info)
                        @php $pct = $totalStatus > 0 ? round(($statusDataValues[$idx] / $totalStatus) * 100, 1) : 0; @endphp
                        <div class="flex items-center justify-between rounded-lg {{ $info['bg'] }} ring-1 {{ $info['ring'] }} px-3 py-2 transition-all hover:shadow-md hover:scale-[1.02]">
                            <div class="flex items-center gap-2.5">
                                <span class="h-3 w-3 rounded-full flex-shrink-0" style="background: {{ $info['color'] }};"></span>
                                <span class="text-sm font-medium text-slate-700">{{ $info['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold {{ $info['text'] }}">{{ number_format($statusDataValues[$idx]) }}</span>
                                <span class="text-[10px] font-semibold text-slate-400">({{ $pct }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        {{-- Lokasi Konsultasi Terbanyak --}}
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Lokasi Konsultasi Terbanyak</h2>
                <span class="text-xs text-slate-500">per Kabupaten/Kota NTB</span>
            </div>
            <div class="h-80">
                <canvas id="consultationLocationsChart"></canvas>
            </div>
        </article>

        {{-- Status Konsultasi --}}
        <article class="rounded-2xl bg-white p-5 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Status Konsultasi</h2>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Live
                </span>
            </div>
            <div class="flex items-center gap-6">
                {{-- Doughnut Chart --}}
                <div class="relative flex-shrink-0" style="width: 220px; height: 220px;">
                    <canvas id="consultationStatusChart"></canvas>
                </div>
                {{-- Legend --}}
                <div class="flex-1 space-y-3">
                    @php
                        $belumDitanggapi = $consultationStatusData['belum'];
                        $sudahDitanggapi = $consultationStatusData['sudah'];
                        $totalKonsultasi = $belumDitanggapi + $sudahDitanggapi;
                        $pctBelum = $totalKonsultasi > 0 ? round(($belumDitanggapi / $totalKonsultasi) * 100, 1) : 0;
                        $pctSudah = $totalKonsultasi > 0 ? round(($sudahDitanggapi / $totalKonsultasi) * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center justify-between rounded-lg bg-amber-50 ring-1 ring-amber-100 px-3 py-2 transition-all hover:shadow-md hover:scale-[1.02]">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full flex-shrink-0" style="background: #f59e0b;"></span>
                            <span class="text-sm font-medium text-slate-700">Belum Ditanggapi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-amber-600">{{ number_format($belumDitanggapi) }}</span>
                            <span class="text-[10px] font-semibold text-slate-400">({{ $pctBelum }}%)</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-emerald-50 ring-1 ring-emerald-100 px-3 py-2 transition-all hover:shadow-md hover:scale-[1.02]">
                        <div class="flex items-center gap-2.5">
                            <span class="h-3 w-3 rounded-full flex-shrink-0" style="background: #10b981;"></span>
                            <span class="text-sm font-medium text-slate-700">Sudah Ditanggapi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-emerald-600">{{ number_format($sudahDitanggapi) }}</span>
                            <span class="text-[10px] font-semibold text-slate-400">({{ $pctSudah }}%)</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-2">
                        <div class="flex items-center justify-between px-1">
                            <span class="text-xs text-slate-400">Total Konsultasi</span>
                            <span class="text-sm font-bold text-navy-700">{{ number_format($totalKonsultasi) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <section class="mt-6 grid gap-6">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Tren Laporan (30 Hari Terakhir)</h2>
                <div class="flex items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full" style="background:#ef4444"></span> Pengaduan</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full" style="background:#3b82f6"></span> Konsultasi</span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="reportTrendChart"></canvas>
            </div>
        </article>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        (() => {
            const charts = @json($charts);

            // Register datalabels plugin globally
            Chart.register(ChartDataLabels);

            const commonOptions = {
                maintainAspectRatio: false,
                plugins: {
                    datalabels: { display: false },
                    legend: {
                        labels: {
                            boxWidth: 12,
                            color: '#334155'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#64748b'
                        },
                        grid: {
                            color: '#e2e8f0'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#64748b',
                            precision: 0
                        },
                        grid: {
                            color: '#e2e8f0'
                        }
                    }
                }
            };

            const topLocationsEl = document.getElementById('topLocationsChart');
            if (topLocationsEl && charts.topLocations.labels.length) {
                new Chart(topLocationsEl, {
                    type: 'bar',
                    data: {
                        labels: charts.topLocations.labels,
                        datasets: [{
                            label: 'Jumlah Aduan',
                            data: charts.topLocations.data,
                            backgroundColor: charts.topLocations.colors.map(c => c + 'cc'),
                            hoverBackgroundColor: charts.topLocations.colors,
                            borderColor: charts.topLocations.colors,
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.75,
                            categoryPercentage: 0.85
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        maintainAspectRatio: false,
                        animation: {
                            duration: 800,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                display: true,
                                anchor: 'end',
                                align: 'end',
                                color: '#334155',
                                font: { weight: 'bold', size: 13 },
                                formatter: (value) => value
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f8fafc',
                                bodyColor: '#f8fafc',
                                cornerRadius: 10,
                                padding: 12,
                                titleFont: { weight: 'bold', size: 13 },
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((ctx.parsed.x / total) * 100).toFixed(1) : 0;
                                        return ` ${ctx.parsed.x} aduan (${pct}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { color: '#94a3b8', precision: 0 },
                                grid: { color: '#f1f5f9', drawBorder: false }
                            },
                            y: {
                                ticks: {
                                    color: '#334155',
                                    font: { weight: '600', size: 12 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            } else if (topLocationsEl) {
                topLocationsEl.parentElement.innerHTML = '<p class="flex items-center justify-center h-full text-slate-400 text-sm">Belum ada data lokasi aduan</p>';
            }

            const complaintStatusEl = document.getElementById('complaintStatusChart');
            if (complaintStatusEl) {
                // Custom plugin to draw total in center 
                const centerTextPlugin = {
                    id: 'centerText',
                    afterDraw(chart) {
                        const { ctx, width, height } = chart;
                        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        const centerX = width / 2;
                        const centerY = height / 2;

                        // Total number
                        ctx.font = 'bold 28px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#1e293b';
                        ctx.fillText(total, centerX, centerY - 8);

                        // Label
                        ctx.font = '500 11px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#94a3b8';
                        ctx.fillText('TOTAL', centerX, centerY + 14);
                        ctx.restore();
                    }
                };

                new Chart(complaintStatusEl, {
                    type: 'doughnut',
                    plugins: [centerTextPlugin],
                    data: {
                        labels: charts.complaintStatus.labels,
                        datasets: [{
                            data: charts.complaintStatus.data,
                            backgroundColor: charts.complaintStatus.colors,
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverBorderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8,
                            spacing: 2
                        }]
                    },
                    options: {
                        cutout: '62%',
                        maintainAspectRatio: false,
                        layout: {
                            padding: 12
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 800,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f8fafc',
                                bodyColor: '#f8fafc',
                                cornerRadius: 10,
                                padding: 12,
                                titleFont: { weight: 'bold', size: 13 },
                                bodyFont: { size: 12 },
                                displayColors: true,
                                boxPadding: 4,
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                        return ` ${context.label}: ${context.parsed} (${pct}%)`;
                                    }
                                }
                            },
                            datalabels: {
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                },
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 13
                                },
                                textShadowColor: 'rgba(0,0,0,0.3)',
                                textShadowBlur: 4,
                                formatter: (value) => value
                            }
                        }
                    }
                });
            }

            // Chart: Lokasi Konsultasi Terbanyak
            const consultationLocationsEl = document.getElementById('consultationLocationsChart');
            if (consultationLocationsEl && charts.consultationLocations.labels.length) {
                new Chart(consultationLocationsEl, {
                    type: 'bar',
                    data: {
                        labels: charts.consultationLocations.labels,
                        datasets: [{
                            label: 'Jumlah Konsultasi',
                            data: charts.consultationLocations.data,
                            backgroundColor: charts.consultationLocations.colors.map(c => c + 'cc'),
                            hoverBackgroundColor: charts.consultationLocations.colors,
                            borderColor: charts.consultationLocations.colors,
                            borderWidth: 1,
                            borderRadius: 8,
                            borderSkipped: false,
                            barPercentage: 0.75,
                            categoryPercentage: 0.85
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        maintainAspectRatio: false,
                        animation: {
                            duration: 800,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                display: true,
                                anchor: 'end',
                                align: 'end',
                                color: '#334155',
                                font: { weight: 'bold', size: 13 },
                                formatter: (value) => value
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f8fafc',
                                bodyColor: '#f8fafc',
                                cornerRadius: 10,
                                padding: 12,
                                titleFont: { weight: 'bold', size: 13 },
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((ctx.parsed.x / total) * 100).toFixed(1) : 0;
                                        return ` ${ctx.parsed.x} konsultasi (${pct}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { color: '#94a3b8', precision: 0 },
                                grid: { color: '#f1f5f9', drawBorder: false }
                            },
                            y: {
                                ticks: {
                                    color: '#334155',
                                    font: { weight: '600', size: 12 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            } else if (consultationLocationsEl) {
                consultationLocationsEl.parentElement.innerHTML = '<p class="flex items-center justify-center h-full text-slate-400 text-sm">Belum ada data lokasi konsultasi</p>';
            }

            // Chart: Status Konsultasi
            const consultationStatusEl = document.getElementById('consultationStatusChart');
            if (consultationStatusEl) {
                const belum = {{ $consultationStatusData['belum'] }};
                const sudah = {{ $consultationStatusData['sudah'] }};

                const consultationCenterPlugin = {
                    id: 'consultationCenter',
                    afterDraw(chart) {
                        const { ctx, width, height } = chart;
                        const total = belum + sudah;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        const centerX = width / 2;
                        const centerY = height / 2;

                        ctx.font = 'bold 28px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#1e293b';
                        ctx.fillText(total, centerX, centerY - 8);
                        ctx.font = '500 11px Inter, system-ui, sans-serif';
                        ctx.fillStyle = '#94a3b8';
                        ctx.fillText('TOTAL', centerX, centerY + 14);
                        ctx.restore();
                    }
                };

                new Chart(consultationStatusEl, {
                    type: 'doughnut',
                    plugins: [consultationCenterPlugin],
                    data: {
                        labels: ['Belum Ditanggapi', 'Sudah Ditanggapi'],
                        datasets: [{
                            data: [belum, sudah],
                            backgroundColor: ['#f59e0b', '#10b981'],
                            hoverBackgroundColor: ['#d97706', '#059669'],
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverBorderColor: '#ffffff',
                            hoverBorderWidth: 4,
                            hoverOffset: 8,
                            spacing: 2
                        }]
                    },
                    options: {
                        cutout: '62%',
                        maintainAspectRatio: false,
                        layout: { padding: 12 },
                        animation: {
                            animateRotate: true,
                            animateScale: true,
                            duration: 800,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                display: function(context) {
                                    return context.dataset.data[context.dataIndex] > 0;
                                },
                                color: '#ffffff',
                                font: { weight: 'bold', size: 13 },
                                formatter: (value) => value
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f8fafc',
                                bodyColor: '#f8fafc',
                                cornerRadius: 10,
                                padding: 12,
                                callbacks: {
                                    label: function(ctx) {
                                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                        const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                        return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const reportTrendEl = document.getElementById('reportTrendChart');
            if (reportTrendEl) {
                const trendData = charts.reportTrend;
                new Chart(reportTrendEl, {
                    type: 'line',
                    data: {
                        labels: trendData.map(d => d.date),
                        datasets: [
                            {
                                label: 'Pengaduan',
                                data: trendData.map(d => d.complaints),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                borderWidth: 2.5,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                                pointBackgroundColor: '#ef4444',
                                fill: true
                            },
                            {
                                label: 'Konsultasi',
                                data: trendData.map(d => d.consultations),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                borderWidth: 2.5,
                                pointRadius: 1,
                                pointHoverRadius: 5,
                                pointBackgroundColor: '#3b82f6',
                                fill: true
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            datalabels: { display: false },
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#f8fafc',
                                bodyColor: '#f8fafc',
                                cornerRadius: 10,
                                padding: 12,
                                titleFont: { weight: 'bold', size: 13 },
                                bodyFont: { size: 12 },
                                displayColors: true,
                                boxPadding: 4
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: '#94a3b8',
                                    maxRotation: 45,
                                    font: { size: 10 }
                                },
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: '#94a3b8',
                                    precision: 0,
                                    stepSize: 1
                                },
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endpush
