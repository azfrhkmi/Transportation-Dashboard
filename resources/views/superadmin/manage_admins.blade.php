@extends('layouts.dashboard')

@section('dashboard_content')
<div class="content-header flex justify-between items-end">
    <div>
        <h2 class="page-title text-white">Manage Admin Users</h2>
        <p class="page-subtitle text-gray-400">View, create, and manage administrator accounts.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Add Admin Form -->
    <div class="glass-panel p-6">
        <h3 class="text-lg font-semibold mb-4 text-white">Add New Admin</h3>
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

            <button type="submit" class="btn-primary w-full py-3 justify-center"><i class="ph ph-user-plus mr-2"></i> Create Admin</button>
        </form>
    </div>

    <!-- Admin Users Table -->
    <div class="glass-panel p-6 lg:col-span-2">
        <h3 class="text-lg font-semibold mb-4 text-white">Registered Users</h3>
        <div class="table-responsive">
            <table class="data-table w-full text-left text-gray-300">
                <thead class="text-xs uppercase bg-[rgba(255,255,255,0.05)] border-b border-[rgba(255,255,255,0.1)]">
                    <tr>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-[rgba(255,255,255,0.05)] hover:bg-[rgba(255,255,255,0.02)]">
                        <td class="p-3 font-medium text-white">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded uppercase {{ $user->role === 'superadmin' ? 'bg-purple-500/20 text-purple-300' : 'bg-blue-500/20 text-blue-300' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            @if($user->id !== auth()->id() && $user->role !== 'superadmin')
                                <form action="{{ route('superadmin.delete-admin', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this admin user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300" title="Delete User">
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
