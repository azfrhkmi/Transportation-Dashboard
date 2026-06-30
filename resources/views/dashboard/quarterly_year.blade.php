@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 class="page-title">Statistics for {{ $year }}</h2>
        <p class="page-subtitle">Detailed breakdown by airport and quarter.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('export.quarterly', $year) }}" class="btn-primary py-2 px-4 rounded text-white bg-green-600 hover:bg-green-700 flex items-center gap-2">
            <i class="ph ph-file-xls text-lg"></i> Download Excel
        </a>
        <a href="{{ route('quarterly') }}" class="btn-secondary py-2 px-4 rounded text-white border border-white">Back to Summary</a>
    </div>
</div>

<div class="table-card glass-panel w-full mt-6">
    <div class="card-header border-b border-[rgba(255,255,255,0.1)] pb-4 mb-4">
        <h3 class="card-title text-xl text-white">Data Table</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table w-full text-left text-gray-300">
            <thead class="text-xs uppercase bg-[rgba(255,255,255,0.05)] border-b border-[rgba(255,255,255,0.1)]">
                <tr>
                    <th class="p-3">Quarter</th>
                    <th class="p-3">Airport</th>
                    <th class="p-3">Domestic (Sched)</th>
                    <th class="p-3">Domestic (Non-Sched)</th>
                    <th class="p-3">Intl (Sched)</th>
                    <th class="p-3">Intl (Non-Sched)</th>
                    <th class="p-3 text-white font-bold">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats as $stat)
                <tr class="border-b border-[rgba(255,255,255,0.05)] hover:bg-[rgba(255,255,255,0.02)]">
                    <td class="p-3">Q{{ $stat->quarter }}</td>
                    <td class="p-3 font-semibold text-white">{{ $stat->airport_name }}</td>
                    <td class="p-3">{{ number_format($stat->domestic_scheduled) }}</td>
                    <td class="p-3">{{ number_format($stat->domestic_non_scheduled) }}</td>
                    <td class="p-3">{{ number_format($stat->international_scheduled) }}</td>
                    <td class="p-3">{{ number_format($stat->international_non_scheduled) }}</td>
                    <td class="p-3 text-white font-bold text-lg">{{ number_format($stat->grand_total) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-500">No data available for {{ $year }}. Admin can upload data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
