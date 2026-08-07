@extends('layouts.app')

@section('content')

<div class="space-y-6">

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                User Management
            </h1>

            <p class="text-slate-500 mt-1">
                Manage administrator and technician accounts.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium transition">

            + Add User

        </a>

    </div>

    <form method="GET">

        <div class="flex gap-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search user..."
                class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

            <button
                class="bg-slate-800 text-white px-6 rounded-xl hover:bg-slate-700">

                Search

            </button>

        </div>

    </form>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Role</th>
                    <th class="p-4 text-center">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)

                <tr class="border-t hover:bg-slate-50">

                    <td class="p-4 font-medium">
                        {{ $user->name }}
                    </td>

                    <td class="p-4">
                        {{ $user->email }}
                    </td>

                    <td class="p-4">

                        @if($user->role=='admin')

                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">

                                Admin

                            </span>

                        @else

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                Technician

                            </span>

                        @endif

                    </td>

                    <td class="p-4">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('users.edit',$user) }}"
                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">

                                Edit

                            </a>

                            <form
                                action="{{ route('users.destroy',$user) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    onclick="return confirm('Delete this user?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">

                                    Delete

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        class="text-center py-12 text-slate-500">

                        No users found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{ $users->links() }}

</div>

@endsection