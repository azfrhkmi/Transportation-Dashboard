@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 class="page-title text-white">Quarterly Statistics</h2>
        <p class="page-subtitle text-gray-400">Historical overview of transport statistics by year.</p>
    </div>
</div>

@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
<div class="glass-panel p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary)">Upload New Data</h3>
    <form action="{{ route('upload.excel') }}" method="POST" enctype="multipart/form-data" class="flex gap-4 items-end">
        @csrf
        <div>
            <label class="block text-sm mb-1 text-gray-500">Year</label>
            <input type="number" name="year" value="2025" class="glass-select p-2 rounded" required>
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

@if($summary->isNotEmpty())
<div class="glass-panel p-6 mb-8 mt-4" style="border: 1px solid rgba(239, 68, 68, 0.3);">
    <h3 class="text-lg font-semibold mb-4 text-red-500"><i class="ph ph-warning"></i> Danger Zone: Delete Uploaded Data</h3>
    <form action="{{ route('delete.data') }}" method="POST" class="flex gap-4 items-end" onsubmit="return confirm('Are you sure you want to delete all uploaded data for this year? This action cannot be undone.');">
        @csrf
        @method('DELETE')
        <div>
            <label class="block text-sm mb-1 text-gray-500">Year to Delete</label>
            <input type="number" name="year" value="2025" class="glass-select p-2 rounded" style="border-color: #ef4444;" required>
        </div>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded transition-colors" style="background-color: #dc2626;">
            <i class="ph ph-trash"></i> Delete All Data for Year
        </button>
    </form>
</div>
@endif
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
