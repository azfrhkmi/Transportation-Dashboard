@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Land Report & Statistic</h2>
        <p class="page-subtitle text-gray-400">Overview of Land and Rail Transport for {{ $targetYear }}.</p>
    </div>
    @if(!empty($years))
    <form action="{{ route('land.index') }}" method="GET" class="flex gap-2 items-center">
        <label class="text-gray-400 text-sm">Filter Year:</label>
        <select name="year" onchange="this.form.submit()" class="glass-select p-2 rounded text-white bg-transparent border border-[rgba(255,255,255,0.2)]">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $targetYear ? 'selected' : '' }} class="bg-gray-800">{{ $y }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Land Licenses Summary -->
    <div class="stat-card glass-panel p-6 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 text-blue-500 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class="ph ph-car-profile" style="font-size: 140px;"></i>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="stat-icon bg-blue-500/20 text-blue-400 p-4 rounded-xl">
                <i class="ph ph-car-profile text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-1">Total Land Licenses</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalLicenses) }}</h3>
            </div>
        </div>
        <div class="mt-6 relative z-10">
            <a href="{{ route('land.licenses', ['year' => $targetYear]) }}" class="text-blue-400 hover:text-blue-300 text-sm font-semibold flex items-center gap-1 transition-colors">
                View Detailed Report <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Rail Passengers Summary -->
    <div class="stat-card glass-panel p-6 relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 text-green-500 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class="ph ph-train" style="font-size: 140px;"></i>
        </div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="stat-icon bg-green-500/20 text-green-400 p-4 rounded-xl">
                <i class="ph ph-train text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-1">Total Rail Passengers</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalPassengers) }}</h3>
            </div>
        </div>
        <div class="mt-6 relative z-10">
            <a href="{{ route('land.rail', ['year' => $targetYear]) }}" class="text-green-400 hover:text-green-300 text-sm font-semibold flex items-center gap-1 transition-colors">
                View Detailed Report <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

@if($totalLicenses == 0 && $totalPassengers == 0)
    <div class="glass-panel p-8 text-center mt-6">
        <i class="ph ph-folder-open text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No data available for {{ $targetYear }}. Navigate to the detailed reports to upload data.</p>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Quarterly Trends Chart -->
        <div class="glass-panel p-6 lg:col-span-2">
            <h3 class="text-white text-lg font-semibold mb-4">Quarterly Transport Trends</h3>
            <div class="relative h-[300px] w-full">
                <canvas id="quarterlyTrendChart"></canvas>
            </div>
        </div>
        
        <!-- License Breakdown Chart -->
        <div class="glass-panel p-6 lg:col-span-1">
            <h3 class="text-white text-lg font-semibold mb-4">License Breakdown</h3>
            <div class="relative h-[300px] w-full flex items-center justify-center">
                <canvas id="licenseDoughnutChart"></canvas>
            </div>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Shared styling
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Inter', sans-serif";

    @if($totalLicenses > 0 || $totalPassengers > 0)
        // 1. Quarterly Trend Chart
        const trendCtx = document.getElementById('quarterlyTrendChart').getContext('2d');
        
        // Gradient for Licenses (Bar)
        let gradientLicenses = trendCtx.createLinearGradient(0, 0, 0, 400);
        gradientLicenses.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        gradientLicenses.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Rail Passengers',
                        data: {!! json_encode($railChartData) !!},
                        borderColor: '#10b981',
                        backgroundColor: '#10b981',
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y1',
                    },
                    {
                        type: 'bar',
                        label: 'Land Licenses',
                        data: {!! json_encode($licenseChartData) !!},
                        backgroundColor: gradientLicenses,
                        borderRadius: 6,
                        borderSkipped: false,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        title: { display: true, text: 'Licenses' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }, // only draw grid lines for one axis
                        title: { display: true, text: 'Passengers' }
                    }
                }
            }
        });

        // 2. License Doughnut Chart
        const doughnutCtx = document.getElementById('licenseDoughnutChart').getContext('2d');
        
        const licenseLabels = {!! json_encode(array_keys($licenseCategories)) !!};
        const licenseData = {!! json_encode(array_values($licenseCategories)) !!};
        
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: licenseLabels,
                datasets: [{
                    data: licenseData,
                    backgroundColor: [
                        '#3b82f6', // blue
                        '#10b981', // green
                        '#8b5cf6', // purple
                        '#f59e0b', // yellow
                        '#ef4444', // red
                        '#06b6d4', // cyan
                        '#6366f1'  // indigo
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 12
                    }
                }
            }
        });
    @endif
});
</script>

@endsection
