@extends('layouts.app')

@section('content')
<div class="app-container">
    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h1 class="logo-text">Malaysia Transportation <span class="text-accent">Reports</span></h1>
        </div>
        
        <div class="menu-container">
            <!-- Aviation Menu -->
            <div class="menu-section">
                <h2 class="menu-title"><i class="ph ph-airplane-tilt"></i> Aviation</h2>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><a href="{{ route('dashboard') }}">Aviation Reports</a></li>
                    <li class="menu-item {{ request()->routeIs('quarterly') ? 'active' : '' }}"><a href="{{ route('quarterly') }}">Quarterly Statistics</a></li>
                </ul>
            </div>
            <!-- Land Menu -->
            <div class="menu-section">
                <h2 class="menu-title"><i class="ph ph-car-profile"></i> Land</h2>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('coming_soon') && request('title') == 'Land Report and Statistic' ? 'active' : '' }}"><a href="{{ route('coming_soon', ['title' => 'Land Report and Statistic']) }}">Land Report & Statistic</a></li>
                    <li class="menu-item {{ request()->routeIs('coming_soon') && request('title') == 'Quarterly Statistics - Land Transport' ? 'active' : '' }}"><a href="{{ route('coming_soon', ['title' => 'Quarterly Statistics - Land Transport']) }}">Quarterly Statistics - Land</a></li>
                    <li class="menu-item {{ request()->routeIs('coming_soon') && request('title') == 'Quarterly Statistics - Rail Transport' ? 'active' : '' }}"><a href="{{ route('coming_soon', ['title' => 'Quarterly Statistics - Rail Transport']) }}">Quarterly Statistics - Rail</a></li>
                </ul>
            </div>

            <!-- Maritime Menu -->
            <div class="menu-section">
                <h2 class="menu-title"><i class="ph ph-boat"></i> Maritime</h2>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('coming_soon') && request('title') == 'Quarterly Statistic of Maritime Transport' ? 'active' : '' }}"><a href="{{ route('coming_soon', ['title' => 'Quarterly Statistic of Maritime Transport']) }}">Quarterly Statistics</a></li>
                </ul>
            </div>

            <!-- Logistic Menu -->
            <div class="menu-section">
                <h2 class="menu-title"><i class="ph ph-package"></i> Logistic</h2>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('coming_soon') && request('title') == 'National Logistic Data Achievement' ? 'active' : '' }}"><a href="{{ route('coming_soon', ['title' => 'National Logistic Data Achievement']) }}">National Logistic Data</a></li>
                </ul>
            </div>

            @if(auth()->check() && auth()->user()->role === 'superadmin')
            <div class="menu-section">
                <h2 class="menu-title"><i class="ph ph-shield"></i> Administration</h2>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('superadmin.add-admin') ? 'active' : '' }}"><a href="{{ route('superadmin.add-admin') }}">Manage Admins</a></li>
                </ul>
            </div>
            @endif
        </div>
        
        <div class="sidebar-footer">
            @auth
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="User Profile" class="avatar">
                <div class="user-info">
                    <span class="user-name text-primary">{{ auth()->user()->name }}</span>
                    <span class="user-role uppercase text-xs">{{ auth()->user()->role }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full btn-secondary text-sm"><i class="ph ph-sign-out"></i> Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="w-full btn-primary text-sm flex items-center justify-center gap-2 py-3 rounded-lg"><i class="ph ph-sign-in"></i> Login</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content bg-main">
        <header class="topbar">
            <button id="menu-toggle" class="menu-toggle" aria-label="Toggle Menu">
                <i class="ph ph-list"></i>
            </button>
            <div class="topbar-search">
                <i class="ph ph-magnifying-glass search-icon"></i>
                <input type="text" placeholder="Search analytics..." class="search-input text-primary">
            </div>
            <div class="topbar-actions ml-auto">
                <button id="theme-toggle" class="icon-btn" aria-label="Toggle Theme">
                    <i class="ph ph-moon text-xl" id="theme-icon"></i>
                </button>
            </div>
        </header>

        <div class="dashboard-area">
            @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-lg">
                {{ session('success') }}
            </div>
            @endif
            
            @yield('dashboard_content')
        </div>
    </main>
</div>
<script src="{{ asset('script.js') }}"></script>
@endsection
