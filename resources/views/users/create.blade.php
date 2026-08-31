@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto text-xs">

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 p-6">

        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h1 class="text-lg font-bold text-slate-800">
                    Add New User
                </h1>
                <p class="text-slate-500 text-[11px] mt-0.5">
                    Create an administrator or user account.
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="text-xs text-[#0072bc] hover:underline font-semibold">
                &larr; Back to Users
            </a>
        </div>

        <form action="{{ route('users.store') }}" method="POST">

            @csrf

            <div class="grid gap-4">

                <div>
                    <label class="block mb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                        Full Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="e.g. John Doe"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-1 focus:ring-[#0072bc] outline-none">
                    @error('name')
                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                        Email Address
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="e.g. john@example.com"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-1 focus:ring-[#0072bc] outline-none">
                    @error('email')
                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                        Role
                    </label>
                    <select
                        name="role"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-1 focus:ring-[#0072bc] outline-none bg-white text-slate-700">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                            User (Standard Access)
                        </option>
                        <option value="technician" {{ old('role') === 'technician' ? 'selected' : '' }}>
                            Technician (Field Staff)
                        </option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                            Admin (Full Access & User Management)
                        </option>
                    </select>
                    @error('role')
                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-600">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Minimum 6 characters"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:ring-1 focus:ring-[#0072bc] outline-none">
                    @error('password')
                        <p class="text-rose-600 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-6 pt-3 border-t border-slate-100">
                <a
                    href="{{ route('users.index') }}"
                    class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 rounded-lg bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-semibold shadow-xs transition">
                    Save User
                </button>
            </div>

        </form>

    </div>

</div>

@endsection