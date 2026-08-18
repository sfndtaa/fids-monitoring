@extends('layouts.app')

@section('content')

<div class="space-y-4">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                Device Inventory
            </h1>
            <p class="text-slate-500 text-xs mt-0.5">
                All registered Airport Flight Information Display devices.
            </p>
        </div>

        <a href="{{ route('monitoring') }}"
            class="px-3.5 py-1.5 rounded-lg bg-[#15803d] hover:bg-[#16a34a] text-white text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Open Network Monitoring</span>
        </a>
    </div>

    <!-- Search & Location Filter -->
    <form method="GET" class="bg-white rounded-lg shadow-xs border border-slate-200 p-3 flex flex-wrap items-center gap-3">

        <div class="relative flex-1 min-w-[240px]">
            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search device name, IP, or location..."
                class="w-full pl-8 pr-3 py-1.5 text-xs rounded-md border border-slate-300 focus:outline-none focus:ring-1 focus:ring-emerald-500">
        </div>

        <select
            name="location"
            class="text-xs rounded-md border border-slate-300 px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-emerald-500">
            <option value="">All Locations / Branches</option>
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
            class="bg-[#15803d] hover:bg-[#16a34a] text-white px-4 py-1.5 text-xs font-semibold rounded-md transition">
            Filter
        </button>

        @if(request('search') || request('location'))
        <a
            href="{{ route('devices') }}"
            class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-1.5 text-xs font-medium rounded-md transition">
            Reset
        </a>
        @endif

    </form>

    <!-- Table of Devices -->
    <div class="bg-white rounded-lg shadow-xs border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-100 text-slate-600 border-b border-slate-200 font-semibold uppercase">
                    <tr>
                        <th class="text-left px-4 py-3">Device Name</th>
                        <th class="text-left px-4 py-3">Location</th>
                        <th class="text-left px-4 py-3">IP Address</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Response Time</th>
                        <th class="text-center px-4 py-3">Last Checked</th>
                        <th class="text-center px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($devices as $device)
                    <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="window.location.href='{{ route('devices.show', $device->id) }}'">
                        <td class="px-4 py-2.5 font-bold text-slate-800">
                            {{ $device->device_name }}
                        </td>

                        <td class="px-4 py-2.5 text-slate-600">
                            {{ $device->location ?? '-' }}
                        </td>

                        <td class="px-4 py-2.5 font-mono text-slate-700 select-all">
                            {{ $device->ip_address }}
                        </td>

                        <td class="px-4 py-2.5 text-center">
                            @if($device->status == 'online')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Online
                                </span>
                            @elseif($device->status == 'warning')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Warning
                                </span>
                            @elseif($device->status == 'maintenance')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                    Maintenance
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-100 text-rose-800 border border-rose-200">
                                    Offline
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-2.5 text-center font-mono">
                            {{ $device->response_time ? $device->response_time . ' ms' : '-' }}
                        </td>

                        <td class="px-4 py-2.5 text-center text-slate-500 font-mono">
                            {{ $device->last_ping ? \Carbon\Carbon::parse($device->last_ping)->format('d M Y H:i:s') : 'Never' }}
                        </td>

                        <td class="px-4 py-2.5 text-center" onclick="event.stopPropagation()">
                            <a href="{{ route('devices.show', $device->id) }}"
                                class="px-2 py-1 rounded bg-[#15803d] hover:bg-[#16a34a] text-white text-[10px] font-medium transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400">
                            No devices found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($devices->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $devices->links() }}
        </div>
        @endif
    </div>

</div>

@endsection