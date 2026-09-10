@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-4 text-xs">

    <!-- Flash message -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-2.5 rounded-xl text-xs flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold text-sm">&times;</button>
    </div>
    @endif

    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-1.5 text-slate-500">
            <a href="{{ route('monitoring') }}" class="hover:text-[#0072bc] transition">Monitoring</a>
            <span>/</span>
            <a href="{{ route('devices') }}" class="hover:text-[#0072bc] transition">Devices</a>
            <span>/</span>
            <span class="text-slate-800 font-bold">{{ $device->device_name }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('devices') }}"
                class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition flex items-center gap-1.5 border border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>

            <!-- Toggle Maintenance Button -->
            <form method="POST" action="{{ route('devices.toggle-maintenance', $device->id) }}" class="inline">
                @csrf
                <button
                    type="submit"
                    onclick="return confirm('{{ $device->status === 'maintenance' ? 'Keluarkan perangkat ini dari mode maintenance dan jalankan ping live?' : 'Tandai perangkat ini sedang dalam MAINTENANCE / Perbaikan Hardware?' }}')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs
                    {{ $device->status === 'maintenance' ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300' }}">
                    <span>🛠️</span>
                    <span>{{ $device->status === 'maintenance' ? 'Selesaikan Maintenance' : 'Set Maintenance' }}</span>
                </button>
            </form>

            <!-- Ping Single Button -->
            <button
                type="button"
                id="btnPingSingle"
                onclick="pingSingleDevice({{ $device->id }})"
                class="px-4 py-1.5 rounded-lg bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <svg id="pingIcon" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span id="pingBtnText">Ping Live Now</span>
            </button>
        </div>
    </div>

    <!-- MAINTENANCE ALERT BANNER (If Active) -->
    @if($device->status === 'maintenance')
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3 shadow-2xs">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-200/80 text-amber-900 flex items-center justify-center text-lg shrink-0">
                🛠️
            </div>
            <div>
                <h3 class="font-bold text-xs text-amber-900">Perangkat Sedang Dalam Mode Maintenance</h3>
                <p class="text-[11px] text-amber-800 mt-0.5">
                    Perangkat ini ditandai sedang dalam jadwal perbaikan / servis fisik oleh teknisi bandara.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('devices.toggle-maintenance', $device->id) }}">
            @csrf
            <button
                type="submit"
                class="px-3.5 py-1.5 rounded-lg bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-bold transition shadow-xs">
                Selesai Servis (Kembali ke Monitoring)
            </button>
        </form>
    </div>
    @endif

    <!-- Main Device Card -->
    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
        
        <!-- Header Info Banner (Angkasa Pura Blue Theme) -->
        <div class="p-5 bg-gradient-to-r from-[#005b9f] via-[#0072bc] to-[#005b9f] text-white flex flex-wrap items-center justify-between gap-4 border-b border-[#005b9f]">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center text-white shrink-0 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M4 5h16v10H4V5Z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl font-bold text-white tracking-tight">{{ $device->device_name }}</h1>
                        <span id="deviceStatusBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ $device->status === 'online' ? 'bg-[#16a34a] text-white' : '' }}
                            {{ $device->status === 'offline' ? 'bg-[#dc2626] text-white' : '' }}
                            {{ $device->status === 'warning' ? 'bg-[#d97706] text-white' : '' }}
                            {{ $device->status === 'maintenance' ? 'bg-slate-600 text-white' : '' }}">
                            {{ $device->status }}
                        </span>
                    </div>
                    <p class="text-blue-100 text-xs mt-0.5 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $device->location ?? 'Unassigned Location' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-right font-mono">
                <div>
                    <span class="text-[10px] text-blue-200 block uppercase">Latency</span>
                    <span id="deviceLatency" class="text-base font-bold text-white">
                        {{ $device->response_time !== null ? $device->response_time . ' ms' : '-' }}
                    </span>
                </div>
                <div class="border-l border-white/20 pl-4">
                    <span class="text-[10px] text-blue-200 block uppercase">Last Checked</span>
                    <span id="deviceLastPing" class="text-xs font-medium text-white">
                        {{ $device->last_ping ? \Carbon\Carbon::parse($device->last_ping)->format('d M Y H:i:s') : 'Never' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Network Specifications Grid -->
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 bg-slate-50">
            
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">IP Address</span>
                <span class="text-sm font-mono font-bold text-slate-800 mt-0.5 block select-all">
                    {{ $device->ip_address }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Static Device IP</span>
            </div>

            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Subnet Mask</span>
                <span class="text-sm font-mono font-bold text-slate-800 mt-0.5 block">
                    {{ $device->subnet ?? '255.255.255.0' }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Network Subnet</span>
            </div>

            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Default Gateway</span>
                <span class="text-sm font-mono font-bold text-slate-800 mt-0.5 block">
                    {{ $device->gateway ?? '-' }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Gateway Router</span>
            </div>

            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Branch / Location</span>
                <span class="text-sm font-semibold text-slate-800 mt-0.5 block truncate" title="{{ $device->location }}">
                    {{ $device->location ?? '-' }}
                </span>
                <span class="text-[10px] text-slate-400 mt-0.5 block">Terminal Floor / Section</span>
            </div>

        </div>

    </div>

    <!-- History Logs Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3.5">

        <!-- Logs Table (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 border-b border-slate-100 pb-2.5">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Monitoring History Logs</h2>
                    <p class="text-[10px] text-slate-400">Recent status checks & latency transitions.</p>
                </div>
                <a href="{{ route('history') }}" class="text-[11px] text-[#0072bc] hover:underline font-bold">View All Logs &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left text-[10px] font-semibold uppercase text-slate-500 border-b border-slate-200 bg-slate-50">
                            <th class="py-2.5 px-3">Time</th>
                            <th class="py-2.5 px-3">Status Transition</th>
                            <th class="py-2.5 px-3 text-center">Latency</th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody" class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2.5 px-3 text-slate-600 font-mono">
                                {{ optional($log->checked_at)->format('d M Y H:i:s') ?? $log->created_at->format('d M Y H:i:s') }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-medium uppercase
                                        {{ $log->old_status === 'online' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $log->old_status === 'offline' ? 'bg-rose-100 text-rose-800' : '' }}
                                        {{ $log->old_status === 'warning' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $log->old_status === 'maintenance' ? 'bg-slate-100 text-slate-700' : '' }}">
                                        {{ $log->old_status ?? '-' }}
                                    </span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase
                                        {{ ($log->new_status ?? $log->status) === 'online' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ ($log->new_status ?? $log->status) === 'offline' ? 'bg-rose-100 text-rose-800' : '' }}
                                        {{ ($log->new_status ?? $log->status) === 'warning' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ ($log->new_status ?? $log->status) === 'maintenance' ? 'bg-slate-100 text-slate-700' : '' }}">
                                        {{ $log->new_status ?? $log->status }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold">
                                {{ $log->response_time !== null ? $log->response_time . ' ms' : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400 text-xs">
                                No history logs recorded yet for this device. Click "Ping Live Now" to test.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alerts Box (1 Col) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 mb-0.5">Device Alerts</h2>
                <p class="text-[10px] text-slate-400 mb-3.5">Downtime or disconnection notifications.</p>

                <div class="space-y-2">
                    @forelse($notifications as $notif)
                    <div class="p-3 rounded-lg border border-rose-200 bg-rose-50/80 flex items-start gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-rose-600 mt-1 shrink-0"></span>
                        <div>
                            <p class="text-[11px] text-rose-900 leading-snug font-medium">{{ $notif->message }}</p>
                            <span class="text-[9px] text-rose-600 mt-0.5 block font-mono">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 rounded-lg border border-slate-200 bg-slate-50 text-center text-slate-400 text-xs">
                        No critical alerts for this device.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 text-[10px] text-slate-400 space-y-1">
                <div class="flex justify-between">
                    <span>Registered Date:</span>
                    <span class="font-medium text-slate-700">{{ $device->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Device Record ID:</span>
                    <span class="font-mono text-slate-700">#{{ $device->id }}</span>
                </div>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    async function pingSingleDevice(deviceId) {
        const btn = document.getElementById('btnPingSingle');
        const icon = document.getElementById('pingIcon');
        const text = document.getElementById('pingBtnText');
        const badge = document.getElementById('deviceStatusBadge');
        const latency = document.getElementById('deviceLatency');
        const lastPing = document.getElementById('deviceLastPing');

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        text.innerText = 'Pinging...';
        icon.classList.add('animate-spin');

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const res = await fetch(`/monitoring/ping/${deviceId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            });

            const data = await res.json();

            if (data.success && data.data) {
                const info = data.data;

                // Update badge
                badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider';
                if (info.status === 'online') {
                    badge.classList.add('bg-[#16a34a]', 'text-white');
                } else if (info.status === 'warning') {
                    badge.classList.add('bg-[#d97706]', 'text-white');
                } else if (info.status === 'maintenance') {
                    badge.classList.add('bg-slate-600', 'text-white');
                } else {
                    badge.classList.add('bg-[#dc2626]', 'text-white');
                }
                badge.innerText = info.status;

                // Update latency & last ping
                latency.innerText = info.response_time !== null ? `${info.response_time} ms` : '-';
                lastPing.innerText = info.last_ping || 'Just now';

                // Prepend log row to table
                const tbody = document.getElementById('logsTableBody');
                if (tbody) {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-slate-50 transition bg-emerald-50/50';
                    tr.innerHTML = `
                        <td class="py-2.5 px-3 text-slate-600 font-mono">${info.last_ping}</td>
                        <td class="py-2.5 px-3">
                            <div class="flex items-center gap-1.5">
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-medium uppercase bg-slate-100 text-slate-700">${info.old_status}</span>
                                <span class="text-slate-400">&rarr;</span>
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase ${info.status === 'online' ? 'bg-emerald-100 text-emerald-800' : (info.status === 'warning' ? 'bg-amber-100 text-amber-800' : (info.status === 'maintenance' ? 'bg-slate-100 text-slate-700' : 'bg-rose-100 text-rose-800'))}">${info.status}</span>
                            </div>
                        </td>
                        <td class="py-2.5 px-3 text-center font-mono font-bold">${info.response_time !== null ? info.response_time + ' ms' : '-'}</td>
                    `;
                    tbody.prepend(tr);
                }

                // Play buzzer alarm if status is Offline, Warning, or Maintenance
                if (info.status === 'offline' || info.status === 'warning' || info.status === 'maintenance') {
                    if (typeof window.playAlertBuzzer === 'function') {
                        window.playAlertBuzzer();
                    }
                }
            }
        } catch (err) {
            console.error('Ping error:', err);
            alert('Failed to ping device. Please check network connection.');
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
            text.innerText = 'Ping Live Now';
            icon.classList.remove('animate-spin');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success') && (str_contains(session('success'), 'MAINTENANCE') || str_contains(session('success'), 'Maintenance')))
            if (typeof window.playAlertBuzzer === 'function') {
                window.playAlertBuzzer();
            }
        @endif
    });
</script>
@endpush

@endsection
