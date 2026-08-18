@extends('layouts.app')

@section('content')

<div class="space-y-4 text-xs">

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-3.5 py-2.5 rounded-lg text-xs flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                User Management
            </h1>
            <p class="text-slate-500 text-xs mt-0.5">
                Manage system administrator and technician accounts.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
            class="bg-[#15803d] hover:bg-[#16a34a] text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add User</span>
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-xs border border-slate-200 p-3">
        <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search user by name or email..."
                    class="w-full pl-8 pr-3 py-1.5 text-xs rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-500 text-slate-800">
            </div>

            <button
                type="submit"
                class="bg-[#15803d] hover:bg-[#16a34a] text-white px-4 rounded-md text-xs font-medium transition">
                Search
            </button>

            @if(request('search'))
            <a
                href="{{ route('users.index') }}"
                class="px-3 py-1.5 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium transition flex items-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-600 font-semibold uppercase">
                        <th class="py-2.5 px-4 text-left">User</th>
                        <th class="py-2.5 px-4 text-left">Email Address</th>
                        <th class="py-2.5 px-4 text-left">Role</th>
                        <th class="py-2.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-2.5 px-4 font-semibold text-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-emerald-100 text-[#15803d] flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>

                        <td class="py-2.5 px-4 text-slate-600">
                            {{ $user->email }}
                        </td>

                        <td class="py-2.5 px-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#15803d]"></span>
                                    Admin
                                </span>
                            @elseif($user->role === 'technician')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-teal-100 text-teal-800 border border-teal-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                    Technician
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                    User
                                </span>
                            @endif
                        </td>

                        <td class="py-2.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('users.edit', $user) }}"
                                    class="px-2.5 py-1 rounded bg-amber-50 hover:bg-amber-100 text-amber-800 text-[10px] font-semibold transition border border-amber-200">
                                    Edit
                                </a>

                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        onclick="return confirm('Are you sure you want to delete user \'{{ $user->name }}\'?')"
                                        class="px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-800 text-[10px] font-semibold transition border border-rose-200">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-slate-400 text-xs">
                            No users found matching query.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-4 py-2.5 border-t border-slate-200 bg-slate-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

@endsection