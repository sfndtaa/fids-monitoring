@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <h1 class="text-3xl font-bold text-slate-800 mb-6">
            Edit User
        </h1>

        <form action="{{ route('users.update',$user) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="grid gap-6">

                <div>

                    <label class="block mb-2 font-medium">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name',$user->name) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email',$user->email) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

                        <option value="admin"
                            {{ $user->role=='admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="technician"
                            {{ $user->role=='technician' ? 'selected' : '' }}>
                            Technician
                        </option>

                    </select>

                </div>

                <div>

                    <label class="block mb-2 font-medium">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Leave blank if unchanged"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3">

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

                    Update User

                </button>

            </div>

        </form>

    </div>

</div>

@endsection