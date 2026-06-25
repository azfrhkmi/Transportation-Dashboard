@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header">
    <div>
        <h2 class="page-title text-white">Add Admin User</h2>
        <p class="page-subtitle text-gray-400">Create a new administrator account.</p>
    </div>
</div>

<div class="glass-panel p-8 max-w-lg mt-6">
    <form method="POST" action="{{ route('superadmin.store-admin') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm mb-2 text-gray-300">Name</label>
            <input type="text" name="name" class="w-full glass-select bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)] text-white p-3 rounded" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm mb-2 text-gray-300">Email (Username)</label>
            <input type="email" name="email" class="w-full glass-select bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)] text-white p-3 rounded" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm mb-2 text-gray-300">Password</label>
            <input type="password" name="password" class="w-full glass-select bg-[rgba(30,41,59,0.5)] border border-[rgba(255,255,255,0.1)] text-white p-3 rounded" required minlength="8">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn-primary w-full py-3 justify-center">Create Admin</button>
    </form>
</div>
@endsection
