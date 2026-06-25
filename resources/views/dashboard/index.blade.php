@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 id="page-title" class="page-title text-white">Aviation Reports</h2>
        <p id="page-subtitle" class="page-subtitle text-gray-400">Overview of aviation transport and related statistics.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card glass-panel">
        <div class="stat-icon" style="background: rgba(59, 130, 246, 0.2); color: #3b82f6;">
            <i class="ph ph-airplane-in-flight"></i>
        </div>
        <div class="stat-details">
            <h3 class="stat-label">Total Movements</h3>
            <p class="stat-value" style="color: var(--text-primary)" id="stat-1-val">{{ number_format($totalFlights) }}</p>
            <span class="text-sm text-gray-400">All recorded flights</span>
        </div>
    </div>
    <div class="stat-card glass-panel">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
            <i class="ph ph-house"></i>
        </div>
        <div class="stat-details">
            <h3 class="stat-label">Domestic</h3>
            <p class="stat-value" style="color: var(--text-primary)" id="stat-2-val">{{ number_format($domesticFlights) }}</p>
            <span class="text-sm text-gray-400">Total domestic flights</span>
        </div>
    </div>
    <div class="stat-card glass-panel">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b;">
            <i class="ph ph-globe-hemisphere-west"></i>
        </div>
        <div class="stat-details">
            <h3 class="stat-label">International</h3>
            <p class="stat-value" style="color: var(--text-primary)" id="stat-3-val">{{ number_format($internationalFlights) }}</p>
            <span class="text-sm text-gray-400">Total international flights</span>
        </div>
    </div>
    <div class="stat-card glass-panel">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6;">
            <i class="ph ph-map-pin"></i>
        </div>
        <div class="stat-details">
            <h3 class="stat-label">Tracked Airports</h3>
            <p class="stat-value" style="color: var(--text-primary)" id="stat-4-val">{{ number_format($airportsCount) }}</p>
            <span class="text-sm text-gray-400">Unique airports</span>
        </div>
    </div>
</div>

<!-- Charts Area -->
<div class="charts-grid">
    <div class="chart-card glass-panel col-span-2">
        <div class="card-header">
            <h3 class="card-title" style="color: var(--text-primary)">Trend Analysis</h3>
        </div>
        <div class="chart-container">
            <canvas id="mainChart"></canvas>
        </div>
    </div>
    <div class="chart-card glass-panel">
        <div class="card-header">
            <h3 class="card-title" style="color: var(--text-primary)">Airport Distribution</h3>
        </div>
        <div class="chart-container">
            <canvas id="doughnutChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to Javascript
    window.dashboardData = {
        trendLabels: @json($trendLabels),
        trendData: @json($trendData),
        doughnutLabels: @json($doughnutLabels),
        doughnutData: @json($doughnutData)
    };
</script>
@endsection
