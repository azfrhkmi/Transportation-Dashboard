@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Maritime Transport Statistics</h2>
        <p class="page-subtitle text-gray-400">Type of Ships Calling by Ports, Malaysia.</p>
    </div>
    @if(!empty($years) && !empty($quarters))
    <form action="{{ route('maritime.index') }}" method="GET" class="flex gap-2 items-center">
        <label class="text-gray-400 text-sm">Filter Year & Quarter:</label>
        <select name="year" onchange="this.form.submit()" class="glass-select p-2 rounded text-white bg-transparent border border-[rgba(255,255,255,0.2)]">
            @foreach($years as $y)
                <option value="{{ $y }}" {{ $y == $targetYear ? 'selected' : '' }} class="bg-gray-800">{{ $y }}</option>
            @endforeach
        </select>
        <select name="quarter" onchange="this.form.submit()" class="glass-select p-2 rounded text-white bg-transparent border border-[rgba(255,255,255,0.2)]">
            @foreach($quarters as $q)
                <option value="{{ $q }}" {{ $q == $targetQuarter ? 'selected' : '' }} class="bg-gray-800">Q{{ $q }}</option>
            @endforeach
        </select>
    </form>
    @endif
</div>

@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin']))
<div class="glass-panel p-6 mb-8 mt-6" style="border: 1px solid rgba(239, 68, 68, 0.3);">
    <h3 class="text-lg font-semibold mb-4 text-red-400"><i class="ph ph-warning-circle"></i> Admin Controls</h3>
    
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div>
            <h4 class="text-white text-sm mb-2">Upload Data</h4>
            <form action="{{ route('upload.maritime') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                @csrf
                <input type="number" name="year" placeholder="Year" value="{{ date('Y') }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <select name="quarter" class="glass-select p-2 rounded text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                    <option value="1">Q1</option>
                    <option value="2">Q2</option>
                    <option value="3">Q3</option>
                    <option value="4">Q4</option>
                </select>
                <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="text-sm text-gray-300 w-full" required>
                <button type="submit" class="btn-primary py-2 px-4 text-sm whitespace-nowrap"><i class="ph ph-upload-simple"></i> Upload</button>
            </form>
        </div>
        <div>
            <h4 class="text-white text-sm mb-2">Delete Data</h4>
            <form action="{{ route('delete.maritime') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL data for this specific quarter?');" class="flex gap-2 items-center">
                @csrf
                @method('DELETE')
                <input type="number" name="year" placeholder="Year" value="{{ $targetYear }}" class="glass-select p-2 rounded w-24 text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                <select name="quarter" class="glass-select p-2 rounded text-white bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)]" required>
                    <option value="1" {{ $targetQuarter == 1 ? 'selected' : '' }}>Q1</option>
                    <option value="2" {{ $targetQuarter == 2 ? 'selected' : '' }}>Q2</option>
                    <option value="3" {{ $targetQuarter == 3 ? 'selected' : '' }}>Q3</option>
                    <option value="4" {{ $targetQuarter == 4 ? 'selected' : '' }}>Q4</option>
                </select>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm whitespace-nowrap transition-colors"><i class="ph ph-trash"></i> Delete Quarter</button>
            </form>
        </div>
    </div>
</div>
@endif

@if($statistics->isNotEmpty())
    <div class="table-card glass-panel w-full mt-6">
        <div class="card-header border-b border-[rgba(255,255,255,0.1)] pb-4 mb-4">
            <h3 class="card-title text-xl text-white">Q{{ $targetQuarter }} Ships Calling by Ports</h3>
        </div>
        <div class="table-responsive">
            <table class="data-table w-full text-left text-gray-300 text-sm">
                <thead class="text-xs uppercase bg-[rgba(255,255,255,0.05)] border-b border-[rgba(255,255,255,0.1)]">
                    <tr>
                        <th rowspan="2" class="p-3 border-r border-[rgba(255,255,255,0.1)]">Port</th>
                        <th colspan="7" class="p-3 text-center border-r border-[rgba(255,255,255,0.1)] bg-[rgba(59,130,246,0.1)]">International</th>
                        <th colspan="7" class="p-3 text-center border-r border-[rgba(255,255,255,0.1)] bg-[rgba(16,185,129,0.1)]">Domestic</th>
                        <th rowspan="2" class="p-3 border-r border-[rgba(255,255,255,0.1)]">Others</th>
                        <th rowspan="2" class="p-3 text-white font-bold bg-[rgba(255,255,255,0.05)]">Grand Total</th>
                    </tr>
                    <tr class="text-[10px]">
                        <!-- International -->
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Mother</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Feeder</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Cargo</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Tanker</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Bulk</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Others</th>
                        <th class="p-2 font-bold text-white border-r border-[rgba(255,255,255,0.1)]">Total</th>
                        <!-- Domestic -->
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Mother</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Feeder</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Cargo</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Tanker</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Bulk</th>
                        <th class="p-2 border-r border-[rgba(255,255,255,0.05)]">Others</th>
                        <th class="p-2 font-bold text-white border-r border-[rgba(255,255,255,0.1)]">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totals = array_fill(0, 16, 0); 
                    @endphp
                    @foreach($statistics as $item)
                        @php
                            $cols = [
                                $item->int_mother, $item->int_feeder, $item->int_cargo, $item->int_tanker, $item->int_bulk, $item->int_others, $item->int_total,
                                $item->dom_mother, $item->dom_feeder, $item->dom_cargo, $item->dom_tanker, $item->dom_bulk, $item->dom_others, $item->dom_total,
                                $item->others, $item->grand_total
                            ];
                            foreach($cols as $k => $v) { $totals[$k] += $v; }
                        @endphp
                    <tr class="border-b border-[rgba(255,255,255,0.05)] hover:bg-[rgba(255,255,255,0.02)]">
                        <td class="p-3 font-medium text-white border-r border-[rgba(255,255,255,0.1)]">{{ $item->port_name }}</td>
                        <!-- International -->
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_mother > 0 ? number_format($item->int_mother) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_feeder > 0 ? number_format($item->int_feeder) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_cargo > 0 ? number_format($item->int_cargo) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_tanker > 0 ? number_format($item->int_tanker) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_bulk > 0 ? number_format($item->int_bulk) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->int_others > 0 ? number_format($item->int_others) : '-' }}</td>
                        <td class="p-2 text-white font-bold border-r border-[rgba(255,255,255,0.1)] bg-[rgba(59,130,246,0.05)]">{{ number_format($item->int_total) }}</td>
                        <!-- Domestic -->
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_mother > 0 ? number_format($item->dom_mother) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_feeder > 0 ? number_format($item->dom_feeder) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_cargo > 0 ? number_format($item->dom_cargo) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_tanker > 0 ? number_format($item->dom_tanker) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_bulk > 0 ? number_format($item->dom_bulk) : '-' }}</td>
                        <td class="p-2 border-r border-[rgba(255,255,255,0.05)]">{{ $item->dom_others > 0 ? number_format($item->dom_others) : '-' }}</td>
                        <td class="p-2 text-white font-bold border-r border-[rgba(255,255,255,0.1)] bg-[rgba(16,185,129,0.05)]">{{ number_format($item->dom_total) }}</td>
                        <!-- Others -->
                        <td class="p-2 border-r border-[rgba(255,255,255,0.1)]">{{ $item->others > 0 ? number_format($item->others) : '-' }}</td>
                        <!-- Grand Total -->
                        <td class="p-2 text-white font-bold bg-[rgba(255,255,255,0.05)]">{{ number_format($item->grand_total) }}</td>
                    </tr>
                    @endforeach
                    <!-- Grand Totals Row -->
                    <tr class="bg-[rgba(255,255,255,0.05)] text-accent font-bold">
                        <td class="p-3 border-r border-[rgba(255,255,255,0.1)]">JUMLAH BESAR</td>
                        @foreach($totals as $index => $t)
                            <td class="p-2 border-r {{ in_array($index, [6, 13, 14, 15]) ? 'border-[rgba(255,255,255,0.1)]' : 'border-[rgba(255,255,255,0.05)]' }}">{{ number_format($t) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="glass-panel p-8 text-center mt-6">
        <i class="ph ph-folder-open text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No data available for Q{{ $targetQuarter }} {{ $targetYear }}. Please upload data using the admin controls.</p>
    </div>
@endif

@endsection
