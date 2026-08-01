@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            Devices
        </h1>

        <p class="text-slate-500 mt-1">
            Monitor all registered Airport Information Display devices.
        </p>

    </div>

    <form method="GET" class="bg-white rounded-2xl shadow-sm p-5 flex flex-wrap gap-4">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search device..."
            class="flex-1 min-w-[250px] rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

        <select
            name="location"
            class="rounded-xl border border-slate-300 px-4 py-3">

            <option value="">All Locations</option>

            @foreach($locations as $location)

                <option
                    value="{{ $location }}"
                    {{ request('location') == $location ? 'selected' : '' }}>

                    {{ $location }}

                </option>

            @endforeach

        </select>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-xl transition">

            Search

        </button>

    </form>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-100">

            <tr>

                <th class="text-left p-4">Device Name</th>
                <th class="text-left p-4">Location</th>
                <th class="text-left p-4">IP Address</th>
                <th class="text-left p-4">Status</th>

            </tr>

            </thead>

            <tbody>

            @forelse($devices as $device)

                <tr class="border-t hover:bg-slate-50">

                    <td class="p-4 font-medium">
                        {{ $device->device_name }}
                    </td>

                    <td class="p-4">
                        {{ $device->location ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $device->ip_address }}
                    </td>

                    <td class="p-4">

                        @if($device->status == 'online')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                Online
                            </span>

                        @elseif($device->status == 'warning')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                                Warning
                            </span>

                        @elseif($device->status == 'maintenance')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                                Maintenance
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                Offline
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="text-center py-8 text-slate-500">
                        No devices found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div>

        {{ $devices->withQueryString()->links() }}

    </div>

</div>

@endsection