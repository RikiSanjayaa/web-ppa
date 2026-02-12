@extends('layouts.admin')

@section('title', 'Ringkasan Pantauan Lokasi')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .chart-container {
            min-height: 350px;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading text-2xl font-bold text-navy-700">Ringkasan Pantauan Lokasi</h1>
                <p class="text-slate-600">Statistik dan grafik sebaran laporan masyarakat.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.location-monitoring.index') }}" 
                   class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Lihat Peta Sebaran
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <!-- Top Locations Chart (Complaints) -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading text-lg font-semibold text-navy-700">Lokasi Pelaporan Terbanyak (Pengaduan)</h3>
                    <span class="text-xs text-slate-500">per Kabupaten/Kota NTB</span>
                </div>
                <div id="topComplaintLocationsChart" class="chart-container"></div>
            </div>

            <!-- Top Locations Chart (Consultations) -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading text-lg font-semibold text-navy-700">Lokasi Konsultasi Terbanyak</h3>
                    <span class="text-xs text-slate-500">per Kabupaten/Kota NTB</span>
                </div>
                <div id="topConsultationLocationsChart" class="chart-container"></div>
            </div>

            <!-- Complaint vs Consultation Pie Chart -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-heading text-lg font-semibold text-navy-700 mb-4">Proporsi Laporan</h3>
                <div id="typeDistributionChart" class="chart-container flex items-center justify-center"></div>
            </div>

            <!-- Daily Trends Chart -->
            <div class="md:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-heading text-lg font-semibold text-navy-700 mb-4">Tren Laporan (30 Hari Terakhir)</h3>
                <div id="dailyTrendChart" class="chart-container"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const regionColors = @json($regionColors);

        // 1. Top Complaint Locations Chart
        const complaintLocations = @json($complaintLocations);
        const complaintNames = complaintLocations.map(item => item.tempat_kejadian);
        const complaintCounts = complaintLocations.map(item => item.total);
        const complaintColors = complaintNames.map(name => regionColors[name] || '#94a3b8');

        function buildLocationChartOptions(names, counts, colors, seriesLabel) {
            const total = counts.reduce((a, b) => a + b, 0);
            return {
                series: [{
                    name: seriesLabel,
                    data: counts
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        horizontal: true,
                        barHeight: '70%',
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    offsetX: 5,
                    style: {
                        fontSize: '13px',
                        fontWeight: 700,
                        colors: ['#334155']
                    },
                    formatter: function(val) {
                        return val;
                    }
                },
                xaxis: {
                    categories: names,
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '11px' }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#334155',
                            fontWeight: 600,
                            fontSize: '12px'
                        }
                    }
                },
                colors: colors,
                legend: { show: false },
                grid: {
                    borderColor: '#f1f5f9',
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } }
                },
                tooltip: {
                    theme: 'dark',
                    style: { fontSize: '13px' },
                    y: {
                        formatter: function(val) {
                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                            return val + ' (' + pct + '%)';
                        }
                    }
                }
            };
        }

        if(complaintNames.length > 0) {
            new ApexCharts(
                document.querySelector("#topComplaintLocationsChart"),
                buildLocationChartOptions(complaintNames, complaintCounts, complaintColors, 'Jumlah Aduan')
            ).render();
        } else {
            document.querySelector("#topComplaintLocationsChart").innerHTML = '<p class="text-center text-slate-500 py-10">Belum ada data lokasi aduan.</p>';
        }

        // 1.5 Top Consultation Locations Chart
        const consultationLocations = @json($consultationLocations);
        const consultationNames = consultationLocations.map(item => item.tempat_kejadian);
        const consultationCounts = consultationLocations.map(item => item.total);
        const consultationColors = consultationNames.map(name => regionColors[name] || '#94a3b8');

        if(consultationNames.length > 0) {
            new ApexCharts(
                document.querySelector("#topConsultationLocationsChart"),
                buildLocationChartOptions(consultationNames, consultationCounts, consultationColors, 'Jumlah Konsultasi')
            ).render();
        } else {
            document.querySelector("#topConsultationLocationsChart").innerHTML = '<p class="text-center text-slate-500 py-10">Belum ada data lokasi konsultasi.</p>';
        }

        // 2. Type Distribution Chart
        const totalComplaints = {{ $totalComplaints }};
        const totalConsultations = {{ $totalConsultations }};

        const optionsTypeDist = {
            series: [totalComplaints, totalConsultations],
            chart: {
                width: 380,
                type: 'pie',
                fontFamily: 'inherit',
            },
            labels: ['Pengaduan', 'Konsultasi'],
            colors: ['#ef4444', '#3b82f6'], // Red for complaints, Blue for consultations
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
             tooltip: {
                theme: 'light'
            }
        };

        if(totalComplaints + totalConsultations > 0) {
            new ApexCharts(document.querySelector("#typeDistributionChart"), optionsTypeDist).render();
        } else {
            document.querySelector("#typeDistributionChart").innerHTML = '<p class="text-center text-slate-500 py-10">Belum ada data laporan.</p>';
        }

        // 3. Daily Trend Chart
        const chartData = @json($chartData);
        const dates = chartData.map(item => item.date);
        const complaintsData = chartData.map(item => item.complaints);
        const consultationsData = chartData.map(item => item.consultations);

        const optionsTrend = {
            series: [{
                name: 'Pengaduan',
                data: complaintsData
            }, {
                name: 'Konsultasi',
                data: consultationsData
            }],
            chart: {
                height: 350,
                type: 'area',
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: dates,
                labels: {
                    rotate: -45,
                    maxHeight: 60
                }
            },
            colors: ['#ef4444', '#3b82f6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                theme: 'light'
            }
        };

        new ApexCharts(document.querySelector("#dailyTrendChart"), optionsTrend).render();
    });
</script>
@endpush
