@extends('layouts.app')

@section('content')

<div class="space-y-4 text-xs">

    <!-- Welcome Header -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                System Overview Dashboard
            </h1>
            <p class="text-slate-500 text-xs mt-0.5">
                Airport Flight Information Display System realtime infrastructure status.
            </p>
        </div>

        <a href="{{ route('monitoring') }}"
            class="bg-[#0072bc] hover:bg-[#005b9f] text-white px-3.5 py-2 rounded-xl text-xs font-semibold shadow-xs transition flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span>Open Network Monitoring Grid</span>
        </a>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">

        <!-- Total -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Total Devices</span>
            <h3 class="text-2xl font-bold text-slate-800 mt-1 font-mono">
                {{ $total }}
            </h3>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Registered FIDS nodes</span>
        </div>

        <!-- Online -->
        <div class="bg-white rounded-xl p-4 border border-emerald-200/80 shadow-xs bg-emerald-50/10">
            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Online (Normal)</span>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1 font-mono">
                {{ $online }}
            </h3>
            <span class="text-[10px] text-emerald-600/80 mt-0.5 block">Reachable & responsive</span>
        </div>

        <!-- Offline -->
        <div class="bg-white rounded-xl p-4 border border-rose-200/80 shadow-xs bg-rose-50/10">
            <span class="text-[10px] font-bold text-rose-800 uppercase tracking-wider block">Offline (Alert)</span>
            <h3 class="text-2xl font-bold text-rose-600 mt-1 font-mono">
                {{ $offline }}
            </h3>
            <span class="text-[10px] text-rose-600/80 mt-0.5 block">Unreachable / timeout</span>
        </div>

        <!-- Warning -->
        <div class="bg-white rounded-xl p-4 border border-amber-200/80 shadow-xs bg-amber-50/10">
            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Warning</span>
            <h3 class="text-2xl font-bold text-amber-600 mt-1 font-mono">
                {{ $warning }}
            </h3>
            <span class="text-[10px] text-amber-600/80 mt-0.5 block">High latency (&gt;300ms)</span>
        </div>

        <!-- Maintenance -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Maintenance</span>
            <h3 class="text-2xl font-bold text-slate-600 mt-1 font-mono">
                {{ $maintenance ?? 0 }}
            </h3>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Under servicing</span>
        </div>

    </div>

    <!-- Bottom Grids -->
    <div class="grid lg:grid-cols-3 gap-4">

        <!-- Recent Status History Logs (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3.5 border-b border-slate-100 pb-2.5">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">
                        Recent Ping & Status Activity
                    </h2>
                    <p class="text-[10px] text-slate-400">Live transitions logged during monitoring checks.</p>
                </div>
                <a href="{{ route('history') }}" class="text-[11px] text-[#0072bc] hover:underline font-bold">
                    View All Logs &rarr;
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentLogs as $log)
                <div class="flex items-center justify-between p-2.5 rounded-lg hover:bg-slate-50 border border-slate-100 transition">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ ($log->new_status ?? $log->status) === 'online' ? 'bg-[#16a34a]' : (($log->new_status ?? $log->status) === 'warning' ? 'bg-[#d97706]' : 'bg-[#dc2626]') }}"></span>
                        <div>
                            <p class="font-bold text-slate-800">{{ $log->device->device_name ?? 'Device #' . $log->device_id }}</p>
                            <p class="text-[10px] text-slate-400 font-mono">{{ $log->device->ip_address ?? '-' }} • {{ $log->device->location ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                            {{ ($log->new_status ?? $log->status) === 'online' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ ($log->new_status ?? $log->status) === 'warning' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ ($log->new_status ?? $log->status) === 'offline' ? 'bg-rose-100 text-rose-800' : '' }}
                            {{ ($log->new_status ?? $log->status) === 'maintenance' ? 'bg-slate-100 text-slate-700' : '' }}">
                            {{ $log->new_status ?? $log->status }}
                        </span>
                        <span class="text-[10px] text-slate-400 block font-mono mt-0.5">
                            {{ optional($log->checked_at)->format('H:i:s') ?? $log->created_at->format('H:i:s') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-slate-400">
                    No ping logs recorded yet. Go to <a href="{{ route('monitoring') }}" class="text-[#0072bc] underline font-semibold">Monitoring</a> to run a network ping sweep.
                </div>
                @endforelse
            </div>
        </div>

        <!-- System & Connectivity Status (1 Col) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 mb-0.5">
                    System Information
                </h2>
                <p class="text-[10px] text-slate-400 mb-3.5">Airport IT infrastructure specifications.</p>

                <div class="space-y-2.5">
                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">ICMP Ping Engine:</span>
                        <span class="font-bold text-[#0072bc] font-mono">
                            Native OS ICMP
                        </span>
                    </div>

                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Database Link:</span>
                        <span class="font-bold text-[#16a34a] font-mono">
                            MySQL Connected
                        </span>
                    </div>

                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Total Monitored Nodes:</span>
                        <span class="font-bold text-slate-800 font-mono">
                            {{ $total }} Nodes
                        </span>
                    </div>

                    <div class="flex justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-slate-500">Theme Identity:</span>
                        <span class="font-bold text-[#0072bc]">
                            Angkasa Pura Light Mode
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('devices') }}" class="block text-center w-full py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                    Manage Devices Inventory &rarr;
                </a>
            </div>
        </div>

    </div>

</div>

@endsection