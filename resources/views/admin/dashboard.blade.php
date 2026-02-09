@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Total Aduan</p>
            <p class="mt-2 text-3xl font-bold text-navy-700">{{ number_format($kpis['total_aduan']) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aduan 7 Hari</p>
            <p class="mt-2 text-3xl font-bold text-coral-600">{{ number_format($kpis['aduan_7_hari']) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Rasio Selesai</p>
            <p class="mt-2 text-3xl font-bold text-teal-600">{{ number_format($kpis['rasio_selesai'], 1) }}%</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aktivitas Admin 7 Hari</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ number_format($kpis['aktivitas_admin_7_hari']) }}</p>
        </article>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Tren Aduan 14 Hari</h2>
                <span class="text-xs text-slate-500">per status</span>
            </div>
            <div class="h-80">
                <canvas id="complaintsTrendChart"></canvas>
            </div>
        </article>

        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Distribusi Status Aduan</h2>
                <span class="text-xs text-slate-500">saat ini</span>
            </div>
            <div class="h-80">
                <canvas id="complaintStatusChart"></canvas>
            </div>
        </article>
    </section>

    <section class="mt-6 grid gap-6">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-heading text-lg font-semibold text-navy-700">Aktivitas Admin 14 Hari</h2>
                <span class="text-xs text-slate-500">jumlah log harian</span>
            </div>
            <div class="h-80">
                <canvas id="adminActivityChart"></canvas>
            </div>
        </article>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const charts = @json($charts);

            const commonOptions = {
                maintainAspectRatio: false,
                plugins: {
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

            const complaintsTrendEl = document.getElementById('complaintsTrendChart');
            if (complaintsTrendEl) {
                new Chart(complaintsTrendEl, {
                    type: 'line',
                    data: {
                        labels: charts.complaintsTrend.labels,
                        datasets: charts.complaintsTrend.datasets.map((dataset) => ({
                            ...dataset,
                            tension: 0.3,
                            borderWidth: 2.5,
                            pointRadius: 2,
                            fill: false
                        }))
                    },
                    options: commonOptions
                });
            }

            const complaintStatusEl = document.getElementById('complaintStatusChart');
            if (complaintStatusEl) {
                new Chart(complaintStatusEl, {
                    type: 'doughnut',
                    data: {
                        labels: charts.complaintStatus.labels,
                        datasets: [{
                            data: charts.complaintStatus.data,
                            backgroundColor: ['#ef4444', '#f59e0b', '#14b8a6']
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    color: '#334155'
                                }
                            }
                        }
                    }
                });
            }

            const adminActivityEl = document.getElementById('adminActivityChart');
            if (adminActivityEl) {
                new Chart(adminActivityEl, {
                    type: 'bar',
                    data: {
                        labels: charts.adminActivity.labels,
                        datasets: [{
                            label: 'Log Aktivitas',
                            data: charts.adminActivity.data,
                            backgroundColor: '#334155'
                        }]
                    },
                    options: commonOptions
                });
            }

            const topActionsEl = document.getElementById('topActionsChart');
            if (topActionsEl && charts.topActions.labels.length) {
                new Chart(topActionsEl, {
                    type: 'bar',
                    data: {
                        labels: charts.topActions.labels,
                        datasets: [{
                            label: 'Total',
                            data: charts.topActions.data,
                            backgroundColor: '#0f766e'
                        }]
                    },
                    options: {
                        ...commonOptions,
                        indexAxis: 'y'
                    }
                });
            }
        })();
    </script>
@endpush
