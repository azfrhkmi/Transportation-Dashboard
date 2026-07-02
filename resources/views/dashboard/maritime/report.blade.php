@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Maritime Report & Statistic</h2>
        <p class="page-subtitle text-gray-400">Overview of Port Activity and Shipping Trends for {{ $targetYear }}.</p>
    </div>
    @if(!empty($years))
    <form action="{{ route('maritime.report') }}" method="GET" class="flex gap-2 items-center">
        <label class="text-gray-400 text-sm">Filter Year:</label>
        <select name="year" onchange="this.form.submit()" class="glass-select p-2 rounded text-white bg-transparent border border-[rgba(255,255,255,0.2)]">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $targetYear ? 'selected' : '' }} class="bg-gray-800">{{ $y }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>

@if($totalShips == 0)
    <div class="glass-panel p-8 text-center mt-6">
        <i class="ph ph-folder-open text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No data available for {{ $targetYear }}. Navigate to the detailed reports to upload data.</p>
    </div>
@else
    <!-- Top Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="stat-card glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-blue-500 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class="ph ph-boat" style="font-size: 140px;"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="stat-icon bg-blue-500/20 text-blue-400 p-4 rounded-xl">
                    <i class="ph ph-boat text-3xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-1">Total Ships</p>
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalShips) }}</h3>
                </div>
            </div>
        </div>

        <div class="stat-card glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-emerald-500 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class="ph ph-globe-hemisphere-east" style="font-size: 140px;"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="stat-icon bg-emerald-500/20 text-emerald-400 p-4 rounded-xl">
                    <i class="ph ph-globe-hemisphere-east text-3xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-1">International</p>
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalInt) }}</h3>
                </div>
            </div>
        </div>

        <div class="stat-card glass-panel p-6 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 text-purple-500 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class="ph ph-map-pin" style="font-size: 140px;"></i>
            </div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="stat-icon bg-purple-500/20 text-purple-400 p-4 rounded-xl">
                    <i class="ph ph-map-pin text-3xl"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm uppercase tracking-wider font-semibold mb-1">Domestic</p>
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalDom) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Top 5 Ports Bar Chart -->
        <div class="glass-panel p-6 lg:col-span-2">
            <h3 class="text-white text-lg font-semibold mb-4">Top 5 Ports by Ship Volume</h3>
            <div class="relative h-[300px] w-full">
                <canvas id="topPortsChart"></canvas>
            </div>
        </div>
        
        <!-- Domestic vs International Doughnut -->
        <div class="glass-panel p-6 lg:col-span-1">
            <h3 class="text-white text-lg font-semibold mb-4">Shipping Distribution</h3>
            <div class="relative h-[300px] w-full flex items-center justify-center">
                <canvas id="shippingDistributionChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <a href="{{ route('maritime.index', ['year' => $targetYear]) }}" class="inline-flex items-center gap-2 btn-primary py-2 px-6 rounded-lg text-sm transition-transform hover:scale-105">
            <i class="ph ph-table"></i> View Detailed Quarterly Statistics
        </a>
    </div>

    <!-- Chart.js Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.font.family = "'Inter', sans-serif";

        // Top 5 Ports Chart
        const barCtx = document.getElementById('topPortsChart').getContext('2d');
        let barGradient = barCtx.createLinearGradient(0, 0, 0, 400);
        barGradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        barGradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($portVolumes->toArray())) !!},
                datasets: [{
                    label: 'Total Ships',
                    data: {!! json_encode(array_values($portVolumes->toArray())) !!},
                    backgroundColor: barGradient,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 12
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: 'rgba(255, 255, 255, 0.05)' } }
                }
            }
        });

        // Distribution Doughnut Chart
        const doughnutCtx = document.getElementById('shippingDistributionChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['International', 'Domestic'],
                datasets: [{
                    data: [{{ $totalInt }}, {{ $totalDom }}],
                    backgroundColor: ['#10b981', '#a855f7'],
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
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
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
    });
    </script>
@endif
@endsection
