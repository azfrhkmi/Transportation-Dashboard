@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 class="page-title">Quarterly Statistics (2014-2026)</h2>
        <p class="page-subtitle">Historical overview of transport statistics.</p>
    </div>
</div>

@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
<div class="glass-panel p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary)">Upload New Data</h3>
    <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data" class="flex gap-4 items-end">
        @csrf
        <div>
            <label class="block text-sm mb-1 text-gray-500">Year</label>
            <input type="number" name="year" value="{{ date('Y') }}" class="glass-select p-2 rounded" required>
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-500">Quarter</label>
            <select name="quarter" class="glass-select p-2 rounded" required>
                <option value="1">Q1</option>
                <option value="2">Q2</option>
                <option value="3" selected>Q3</option>
                <option value="4">Q4</option>
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-500">Excel File</label>
            <input type="file" name="excel_file" accept=".xlsx, .xls, .csv" style="color: var(--text-primary)" required>
        </div>
        <button type="submit" class="btn-primary py-2 px-6">Upload & Process</button>
    </form>
    @error('excel_file')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($years as $year)
        <a href="{{ route('quarterly', $year) }}" class="stat-card glass-panel flex flex-col transition-all cursor-pointer">
            <div class="stat-details w-full text-center">
                <h3 class="stat-label text-lg">Year {{ $year }}</h3>
                <p class="stat-value text-3xl mt-2" style="color: var(--text-primary)">
                    {{ isset($summary[$year]) ? number_format($summary[$year]->total) : '0' }}
                </p>
                <span class="text-sm text-gray-400">Total Movements</span>
            </div>
        </a>
    @endforeach
</div>
@endsection
