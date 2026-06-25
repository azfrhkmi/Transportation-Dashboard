@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 class="page-title">{{ $title ?? 'Section Title' }}</h2>
        <p class="page-subtitle">This section is currently under construction.</p>
    </div>
</div>

<div class="glass-panel p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
    <div class="text-6xl text-brand-500 mb-6 opacity-50">
        <i class="ph ph-cone"></i>
    </div>
    <h3 class="text-2xl font-semibold mb-2">Awaiting Data</h3>
    <p class="text-gray-400 max-w-md mx-auto">
        The database tables and analytics pipelines for the <strong>{{ $title ?? 'this sector' }}</strong> are still being developed. Once the Excel formats are finalized, this page will display full analytics.
    </p>
    <a href="{{ route('dashboard') }}" class="btn-primary mt-8 inline-flex">Return to Aviation Dashboard</a>
</div>
@endsection
