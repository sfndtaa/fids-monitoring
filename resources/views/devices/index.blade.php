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

    <form method="GET" class="bg-white rounded-2xl shadow-sm p-5 flex flex-wrap items-center gap-4">

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search device, IP, or location..."
            class="flex-1 min-w-[260px] rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select
            name="location"
            class="rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

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
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition">

            Search

        </button>

        <a
            href="{{ route('devices') }}"
            class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-3 rounded-xl transition">

            Reset

        </a>

    </form>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-100 text-slate-700">

                <tr>

                    <th class="text-left px-6 py-4 font-semibold">
                        Device Name
                    </th>

                    <th class="text-left px-6 py-4 font-semibold">
                        Location
                    </th>

                    <th class="text-left px-6 py-4 font-semibold">
                        IP Address
                    </th>

                    <th class="text-center px-6 py-4 font-semibold">
                        Status
                    </th>

                    <th class="text-center px-6 py-4 font-semibold">
                        Response
                    </th>

                    <th class="text-center px-6 py-4 font-semibold">
                        Last Ping
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($devices as $device)

                    <tr class="border-t hover:bg-slate-50 transition">

                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $device->device_name }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $device->location ?? '-' }}
                        </td>

                        <td class="px-6 py-4 font-mono text-sm">
                            {{ $device->ip_address }}
                        </td>

                        <td class="px-6 py-4 text-center">

                            @if($device->status == 'online')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                    Online
                                </span>

                            @elseif($device->status == 'warning')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">
                                    Warning
                                </span>

                            @elseif($device->status == 'maintenance')

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                                    Maintenance
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                    Offline
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            @if($device->response_time)

                                {{ $device->response_time }} ms

                            @else

                                -

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center text-sm text-slate-500">

                            @if($device->last_ping)

                                {{ \Carbon\Carbon::parse($device->last_ping)->format('d M Y H:i') }}

                            @else

                                Never

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-10 text-slate-500">

                            No devices found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="flex justify-end">

        {{ $devices->links() }}

    </div>

</div>

@endsection