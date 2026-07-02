@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Rail Transport Statistics</h2>
        <p class="page-subtitle text-gray-400">Number of Passengers for Rail Transport Services.</p>
    </div>
    @if(!empty($years))
    <form action="{{ route('land.rail') }}" method="GET" class="flex gap-2 items-center">
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
            <form action="{{ route('upload.rail') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                @csrf
                <input type="number" name="year" placeholder="Year" value="{{ date('Y') }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="text-sm text-gray-300" required>
                <button type="submit" class="btn-primary py-2 px-4 text-sm whitespace-nowrap"><i class="ph ph-upload-simple"></i> Upload</button>
            </form>
        </div>
        <div>
            <h4 class="text-white text-sm mb-2">Delete Data</h4>
            <form action="{{ route('delete.rail') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL data for this year?');" class="flex gap-2 items-center">
                @csrf
                @method('DELETE')
                <input type="number" name="year" placeholder="Year" value="{{ $targetYear }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm whitespace-nowrap transition-colors"><i class="ph ph-trash"></i> Delete Year</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($passengers->isNotEmpty())
    <div class="table-card glass-panel w-full mt-6">
        <div class="card-header border-b border-[rgba(255,255,255,0.1)] pb-4 mb-4">
            <h3 class="card-title text-xl text-white">Passenger Breakdown</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table w-full text-left text-gray-300">
                <thead class="text-xs uppercase bg-[rgba(255,255,255,0.05)] border-b border-[rgba(255,255,255,0.1)]">
                    <tr>
                        <th class="p-3">Type of Services</th>
                        <th class="p-3">First Quarter</th>
                        <th class="p-3">Second Quarter</th>
                        <th class="p-3">Third Quarter</th>
                        <th class="p-3">Fourth Quarter</th>
                        <th class="p-3 text-white font-bold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sumQ1=0; $sumQ2=0; $sumQ3=0; $sumQ4=0; $grandTotal=0; @endphp
                    @foreach($passengers as $item)
                        @php
                            $rowTotal = $item->q1 + $item->q2 + $item->q3 + $item->q4;
                            $sumQ1 += $item->q1; $sumQ2 += $item->q2; $sumQ3 += $item->q3; $sumQ4 += $item->q4; $grandTotal += $rowTotal;
                        @endphp
                    <tr class="border-b border-[rgba(255,255,255,0.05)] hover:bg-[rgba(255,255,255,0.02)]">
                        <td class="p-3 font-medium text-white">{{ $item->service_type }}</td>
                        <td class="p-3">{{ number_format($item->q1) }}</td>
                        <td class="p-3">{{ number_format($item->q2) }}</td>
                        <td class="p-3">{{ number_format($item->q3) }}</td>
                        <td class="p-3">{{ number_format($item->q4) }}</td>
                        <td class="p-3 text-white font-bold">{{ number_format($rowTotal) }}</td>
                    </tr>
                    @endforeach
                    <!-- Grand Total Row -->
                    <tr class="bg-[rgba(255,255,255,0.05)]">
                        <td class="p-3 font-bold text-accent">JUMLAH (Total)</td>
                        <td class="p-3 font-bold text-white">{{ number_format($sumQ1) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($sumQ2) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($sumQ3) }}</td>
                        <td class="p-3 font-bold text-white">{{ number_format($sumQ4) }}</td>
                        <td class="p-3 font-bold text-accent text-lg">{{ number_format($grandTotal) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="glass-panel p-8 text-center mt-6">
        <i class="ph ph-folder-open text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No data available for {{ $targetYear }}. Please upload data using the admin controls.</p>
    </div>
@endif

@endsection
