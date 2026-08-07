@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h1 class="text-3xl font-bold text-slate-800 mb-6">
            Add User
        </h1>

        <form action="{{ route('users.store') }}" method="POST">

            @csrf

            <div class="grid gap-6">

                <div>

                    <label class="block mb-2 font-medium">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <option value="technician">
                            Technician
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8">

                <a
                    href="{{ route('users.index') }}"
                    class="px-5 py-3 rounded-xl bg-slate-200 hover:bg-slate-300">

                    Cancel

                </a>

                <button
                    class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white">

                    Save User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection