@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Land Transport Statistics</h2>
        <p class="page-subtitle text-gray-400">Number of Licenses Issued by Class of Licenses.</p>
    </div>
    @if(!empty($years))
    <form action="{{ route('land.licenses') }}" method="GET" class="flex gap-2 items-center">
        <label class="text-gray-400 text-sm">Filter Year:</label>
        <select name="year" onchange="this.form.submit()" class="glass-select p-2 rounded text-white bg-transparent border border-[rgba(255,255,255,0.2)]">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $targetYear ? 'selected' : '' }} class="bg-gray-800">{{ $y }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>

@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
<div class="glass-panel p-6 mb-8 mt-6" style="border: 1px solid rgba(239, 68, 68, 0.3);">
    <h3 class="text-lg font-semibold mb-4 text-red-400"><i class="ph ph-warning-circle"></i> Admin Controls</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="text-white text-sm mb-2">Upload Data</h4>
            <form action="{{ route('upload.land') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                @csrf
                <input type="number" name="year" placeholder="Year" value="{{ date('Y') }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="text-sm text-gray-300" required>
                <button type="submit" class="btn-primary py-2 px-4 text-sm whitespace-nowrap"><i class="ph ph-upload-simple"></i> Upload</button>
            </form>
        </div>
        <div>
            <h4 class="text-white text-sm mb-2">Delete Data</h4>
            <form action="{{ route('delete.land') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL data for this year?');" class="flex gap-2 items-center">
                @csrf
                @method('DELETE')
                <input type="number" name="year" placeholder="Year" value="{{ $targetYear }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm whitespace-nowrap transition-colors"><i class="ph ph-trash"></i> Delete Year</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($licenses->isNotEmpty())
    @foreach($licenses as $category => $items)
    <div class="table-card glass-panel w-full mt-6">
        <div class="card-header border-b border-[rgba(255,255,255,0.1)] pb-4 mb-4">
            <h3 class="card-title text-xl text-white uppercase">{{ $category }}</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table w-full text-left text-gray-300">
                <thead class="text-xs uppercase bg-[rgba(255,255,255,0.05)] border-b border-[rgba(255,255,255,0.1)]">
                    <tr>
                        <th class="p-3">Class of Licenses</th>
                        <th class="p-3">Q1</th>
                        <th class="p-3">Q2</th>
                        <th class="p-3">Q3</th>
                        <th class="p-3">Q4</th>
                        <th class="p-3 text-white font-bold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $catQ1=0; $catQ2=0; $catQ3=0; $catQ4=0; $catTotal=0; @endphp
                    @foreach($items as $item)
                        @php
                            $rowTotal = $item->q1 + $item->q2 + $item->q3 + $item->q4;
                            $catQ1 += $item->q1; $catQ2 += $item->q2; $catQ3 += $item->q3; $catQ4 += $item->q4; $catTotal += $rowTotal;
                        @endphp
                    <tr class="border-b border-[rgba(255,255,255,0.05)] hover:bg-[rgba(255,255,255,0.02)]">
                        <td class="p-3 font-medium text-white">{{ $item->license_type }}</td>
                        <td class="p-3">{{ number_format($item->q1) }}</td>
                        <td class="p-3">{{ number_format($item->q2) }}</td>
                        <td class="p-3">{{ number_format($item->q3) }}</td>
                        <td class="p-3">{{ number_format($item->q4) }}</td>
                        <td class="p-3 text-white font-bold">{{ number_format($rowTotal) }}</td>
                    </tr>
                    @endforeach
                    <!-- Subtotal Row -->
                    <tr class="bg-[rgba(255,255,255,0.02)]">
                        <td class="p-3 font-bold text-accent">SUB TOTAL</td>
                        <td class="p-3 font-bold text-white">{{ number_format($catQ1) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($catQ2) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($catQ3) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($catQ4) }}</td>
                        <td class="p-3 font-bold text-accent">{{ number_format($catTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@else
    <div class="glass-panel p-8 text-center mt-6">
        <i class="ph ph-folder-open text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No data available for {{ $targetYear }}. Please upload data using the admin controls.</p>
    </div>
@endif

@endsection
