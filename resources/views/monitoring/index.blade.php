@extends('layouts.app')

@section('content')

<div class="space-y-3">

    <!-- Top Toolbar (Angkasa Pura Light Theme) -->
    <div class="bg-white text-slate-800 rounded-xl shadow-xs border border-slate-200 px-4 py-3">
        <form method="GET" action="{{ route('monitoring') }}" id="filterForm" class="flex flex-col gap-2.5 text-xs">
            
            <!-- Tier 1: System Info (Live Clock / Auto-Refresh) & Primary Actions (Sound, Refresh, Scan) -->
            <div class="flex flex-wrap items-center justify-between gap-3">
                
                <!-- Left: Live Clock & Auto-refresh status -->
                <div class="flex items-center gap-2 font-mono text-[11px] select-none shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200 tabular-nums">
                        <span class="w-2 h-2 rounded-full bg-[#0072bc] animate-pulse"></span>
                        <span id="lastRefreshDisplay" class="font-semibold">[ Jam: {{ now()->format('H:i:s') }} ]</span>
                    </span>
                    <span class="text-slate-500 tabular-nums hidden sm:inline px-1">
                        [ Auto-Refresh in <span id="autoRefreshCountdown" class="text-[#0072bc] font-bold inline-block min-w-[2ch] text-center">45</span>s ]
                    </span>
                </div>

                <!-- Right: Action Buttons & Sound Control -->
                <div class="flex items-center gap-2 shrink-0">
                    
                    <!-- Sound Control Toggle (Muted / Unmuted) -->
                    <button
                        type="button"
                        id="btnToggleSound"
                        onclick="toggleSoundControl()"
                        class="px-2.5 py-1 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-[11px] font-semibold transition flex items-center gap-1.5 shadow-2xs"
                        title="Toggle Alert Notification Sound">
                        <span id="soundIcon">🔊</span>
                        <span id="soundLabel">Unmuted</span>
                    </button>

                    <!-- Refresh Button -->
                    <button
                        type="button"
                        id="btnRefreshStatus"
                        onclick="refreshStatusData()"
                        class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-medium transition flex items-center gap-1 border border-slate-200"
                        title="Reload latest status from database">
                        <svg id="refreshSpinner" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Refresh</span>
                    </button>

                    <!-- Ping Sweep Button (Angkasa Pura Blue) -->
                    <button
                        type="button"
                        id="btnStartBatchPing"
                        onclick="openPingModal()"
                        class="px-3.5 py-1 rounded-lg bg-[#0072bc] hover:bg-[#005b9f] text-white text-[11px] font-bold transition flex items-center gap-1.5 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Ping Sweep (Scan LAN)</span>
                    </button>
                </div>
            </div>

            <!-- Tier 2: View Switcher (Left) & Filters / Search (Right) -->
            <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2.5">
                
                <!-- Left: View Mode Switcher (Tree View vs Card Grid View) -->
                <div class="inline-flex p-0.5 bg-slate-100 rounded-lg border border-slate-200 shrink-0">
                    <button
                        type="button"
                        onclick="setViewMode('tree')"
                        id="tabTree"
                        class="px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 {{ $viewMode !== 'table' ? 'bg-[#0072bc] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                        </svg>
                        <span>Tree View (Icon)</span>
                    </button>

                    <button
                        type="button"
                        onclick="setViewMode('table')"
                        id="tabTable"
                        class="px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 {{ $viewMode === 'table' ? 'bg-[#0072bc] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Card Grid View</span>
                    </button>
                </div>
                <input type="hidden" name="view" id="viewModeInput" value="{{ $viewMode }}">

                <!-- Right: Filters & Search Input -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Status Filter -->
                    <select
                        name="status"
                        id="statusSelect"
                        onchange="document.getElementById('filterForm').submit()"
                        class="text-[11px] font-medium rounded-lg border border-slate-300 bg-white text-slate-700 pl-2.5 pr-8 py-1 focus:outline-none focus:ring-1 focus:ring-[#0072bc] cursor-pointer">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Status: All ({{ $stats['total'] }})</option>
                        <option value="online" {{ $statusFilter === 'online' ? 'selected' : '' }}>Online ({{ $stats['online'] }})</option>
                        <option value="offline" {{ $statusFilter === 'offline' ? 'selected' : '' }}>Offline ({{ $stats['offline'] }})</option>
                        <option value="warning" {{ $statusFilter === 'warning' ? 'selected' : '' }}>Warning ({{ $stats['warning'] }})</option>
                        <option value="maintenance" {{ $statusFilter === 'maintenance' ? 'selected' : '' }}>Maintenance ({{ $stats['maintenance'] }})</option>
                    </select>

                    <!-- Branch / Location Filter -->
                    <select
                        name="location"
                        id="locationSelect"
                        onchange="document.getElementById('filterForm').submit()"
                        class="text-[11px] font-medium rounded-lg border border-slate-300 bg-white text-slate-700 pl-2.5 pr-8 py-1 focus:outline-none focus:ring-1 focus:ring-[#0072bc] max-w-[180px] truncate cursor-pointer">
                        <option value="all" {{ $locationFilter === 'all' ? 'selected' : '' }}>Branch: All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ $locationFilter === $loc ? 'selected' : '' }}>
                                {{ $loc }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Input -->
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            id="searchInput"
                            value="{{ $search }}"
                            placeholder="Search device/IP..."
                            class="pl-7 pr-2.5 py-1 text-[11px] rounded-lg border border-slate-300 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#0072bc] w-36 sm:w-44">
                        <span class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                    </div>
                </div>

            </div>

        </form>
    </div>

    <!-- Status Legend Summary Bar -->
    <div class="bg-white rounded-xl border border-slate-200 px-4 py-2 flex flex-wrap items-center justify-between gap-2.5 text-[11px] shadow-2xs">
        <div class="flex items-center gap-2 font-bold text-slate-800">
            <span class="w-2.5 h-2.5 rounded-full bg-[#0072bc]"></span>
            <span>Live Status:</span>
        </div>

        <div class="flex flex-wrap items-center gap-4 font-medium">
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-[#16a34a] border border-[#15803d] inline-block shadow-2xs"></span>
                <span>Normal / Online: <strong id="legendOnline" class="font-mono text-emerald-700 font-bold">{{ $stats['online'] }}</strong></span>
            </span>

            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-[#dc2626] border border-[#b91c1c] inline-block shadow-2xs"></span>
                <span>Alert / Offline: <strong id="legendOffline" class="font-mono text-rose-700 font-bold">{{ $stats['offline'] }}</strong></span>
            </span>

            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-[#d97706] border border-[#b45309] inline-block shadow-2xs"></span>
                <span>Warning: <strong id="legendWarning" class="font-mono text-amber-700 font-bold">{{ $stats['warning'] }}</strong></span>
            </span>

            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-[#64748b] border border-[#475569] inline-block shadow-2xs"></span>
                <span>Maintenance: <strong id="legendMaintenance" class="font-mono text-slate-700 font-bold">{{ $stats['maintenance'] }}</strong></span>
            </span>

            <span class="border-l border-slate-200 pl-3 text-slate-500">
                Total: <strong id="legendTotal" class="font-mono text-slate-900 font-bold">{{ $stats['total'] }}</strong>
            </span>
        </div>
    </div>

    <!-- VIEW 1: NOC TREE VIEW (ULTRA-DENSE ICON VIEW) -->
    <div id="treeViewContainer" class="{{ $viewMode === 'table' ? 'hidden' : 'space-y-3' }}">
        
        <!-- Master Tree Title Header Bar -->
        <div class="bg-gradient-to-r from-[#005b9f] via-[#0072bc] to-[#005b9f] text-white px-4 py-2 rounded-xl shadow-xs border border-[#005b9f]/30 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-400"></span>
                <h2 class="font-bold text-xs md:text-sm text-white tracking-wide">
                    Tree: SAMS Sepinggan Airport FIDS Network Infrastructure
                </h2>
            </div>
            <span class="text-[10px] bg-white/20 px-2.5 py-0.5 rounded-full text-white font-mono font-medium">
                {{ $devices->count() }} Devices Monitored
            </span>
        </div>

        <!-- Branches / Groups -->
        @forelse($groupedDevices as $locationName => $devList)
        @php
            $groupOnline = $devList->where('status', 'online')->count();
            $groupOffline = $devList->where('status', 'offline')->count();
            $groupWarning = $devList->where('status', 'warning')->count();
            $groupTotal = $devList->count();
        @endphp

        <div class="branch-card bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden" data-branch="{{ $locationName }}">
            
            <!-- Light Branch Ribbon Bar (Angkasa Pura Blue Accent) -->
            <div class="px-3.5 py-1.5 bg-gradient-to-r from-[#0072bc] via-[#0084d6] to-[#0072bc] text-white flex flex-wrap items-center justify-between gap-2 border-b border-[#005b9f]">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-xs bg-emerald-300"></span>
                    <h3 class="font-bold text-xs text-white tracking-tight flex items-center gap-1.5">
                        <span>Branch: {{ $locationName }}</span>
                        <span class="text-[10px] font-normal text-blue-100">({{ $groupTotal }})</span>
                    </h3>
                </div>

                <!-- Sub-counters -->
                <div class="flex items-center gap-2.5 text-[10px] font-mono branch-stats">
                    <span class="text-emerald-200 font-semibold branch-count-online">{{ $groupOnline > 0 ? $groupOnline . ' OK' : '' }}</span>
                    <span class="text-amber-200 font-bold branch-count-warning">{{ $groupWarning > 0 ? '● ' . $groupWarning . ' Warn' : '' }}</span>
                    <span class="text-rose-200 font-bold branch-count-offline">{{ $groupOffline > 0 ? '● ' . $groupOffline . ' Down' : '' }}</span>
                </div>
            </div>

            <!-- Ultra-dense Device Icons Grid Container -->
            <div class="p-2.5 bg-white overflow-x-auto">
                <div class="device-grid flex flex-wrap gap-x-2 gap-y-2.5 items-start content-start">
                    
                    @foreach($devList as $device)
                    <div
                        id="node-device-{{ $device->id }}"
                        data-device-id="{{ $device->id }}"
                        data-status="{{ $device->status }}"
                        class="device-node group relative flex flex-col items-center justify-start text-center cursor-pointer transition-transform duration-100 hover:scale-110 p-1 rounded-lg hover:bg-slate-100/70"
                        style="width: 58px;"
                        onclick="window.location.href='{{ route('devices.show', $device->id) }}'">
                        
                        <!-- RACK SERVER / SWITCH SVG ICON -->
                        <div class="node-icon-box relative">
                            
                            <!-- Compact SVG Rack Icon (Green / Red / Amber / Slate) -->
                            <svg class="node-svg w-7 h-5 transition-all duration-150
                                {{ $device->status === 'online' ? 'text-[#16a34a] drop-shadow-2xs' : '' }}
                                {{ $device->status === 'offline' ? 'text-[#dc2626] animate-pulse' : '' }}
                                {{ $device->status === 'warning' ? 'text-[#d97706]' : '' }}
                                {{ $device->status === 'maintenance' ? 'text-[#64748b]' : '' }}"
                                viewBox="0 0 44 26"
                                fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <!-- Outer Chassis -->
                                <rect x="1" y="1" width="42" height="24" rx="3" fill="currentColor" stroke="#000" stroke-width="1.2" stroke-opacity="0.25"/>
                                <!-- Drive Slots -->
                                <rect x="4" y="5" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <rect x="14" y="5" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <rect x="4" y="11" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <rect x="14" y="11" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <rect x="4" y="17" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <rect x="14" y="17" width="8" height="4" rx="0.5" fill="#ffffff" fill-opacity="0.85"/>
                                <!-- Vents / Ports -->
                                <rect x="25" y="5" width="10" height="7" rx="0.5" fill="#ffffff" fill-opacity="0.5"/>
                                <rect x="25" y="14" width="10" height="7" rx="0.5" fill="#ffffff" fill-opacity="0.5"/>
                                <!-- Status LEDs -->
                                <circle cx="39" cy="8" r="2" fill="#ffffff" fill-opacity="0.95"/>
                                <circle cx="39" cy="17" r="1.5" fill="#ffffff" fill-opacity="0.6"/>
                            </svg>

                            <!-- Quick Ping Button (Hover) -->
                            <button
                                type="button"
                                title="Ping now"
                                onclick="event.stopPropagation(); pingNodeDevice({{ $device->id }})"
                                class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-[#0072bc] hover:bg-[#005b9f] text-white flex items-center justify-center text-[8px] opacity-0 group-hover:opacity-100 transition z-10 shadow-xs">
                                ⚡
                            </button>
                        </div>

                        <!-- Device Name Label -->
                        <span class="node-label text-[9px] font-medium leading-[11px] text-slate-700 mt-1 break-words line-clamp-2 max-w-[56px] group-hover:text-[#0072bc] transition" title="{{ $device->device_name }} ({{ $device->ip_address }})">
                            {{ $device->device_name }}
                        </span>

                        <!-- Custom Hover Popover Tooltip -->
                        <div class="pointer-events-none group-hover:pointer-events-auto opacity-0 group-hover:opacity-100 transition-opacity duration-150 absolute bottom-full mb-1.5 left-1/2 -translate-x-1/2 w-48 bg-slate-900 text-white text-left p-2.5 rounded-xl shadow-xl z-50 border border-slate-700">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-1 mb-1.5">
                                <span class="font-bold text-[11px] truncate text-white">{{ $device->device_name }}</span>
                                <span class="node-tooltip-badge px-1.5 py-0.2 rounded text-[8px] font-bold uppercase
                                    {{ $device->status === 'online' ? 'bg-emerald-900 text-emerald-300' : '' }}
                                    {{ $device->status === 'offline' ? 'bg-rose-900 text-rose-300' : '' }}
                                    {{ $device->status === 'warning' ? 'bg-amber-900 text-amber-300' : '' }}
                                    {{ $device->status === 'maintenance' ? 'bg-slate-800 text-slate-300' : '' }}">
                                    {{ $device->status }}
                                </span>
                            </div>
                            <div class="space-y-0.5 text-[9px] text-slate-300 font-mono">
                                <div>IP: <span class="text-white font-semibold">{{ $device->ip_address }}</span></div>
                                <div>Latency: <span class="node-tooltip-latency text-emerald-400 font-bold">{{ $device->response_time !== null ? $device->response_time . ' ms' : '-' }}</span></div>
                                <div>Checked: <span class="node-tooltip-checked text-slate-400">{{ $device->last_ping ? \Carbon\Carbon::parse($device->last_ping)->format('H:i:s') : 'never' }}</span></div>
                            </div>
                        </div>

                    </div>
                    @endforeach

                </div>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-xl p-8 text-center border border-slate-200 text-slate-400 text-xs">
            No devices found matching filter.
        </div>
        @endforelse

    </div>

    <!-- VIEW 2: CARD GRID VIEW (KOTAK-KOTAK TERSTRUKTUR LIGHT MODE) -->
    <div id="tableViewContainer" class="{{ $viewMode === 'table' ? 'space-y-4' : 'hidden' }}">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
            
            @forelse($tableDevices as $device)
            <div
                id="card-device-{{ $device->id }}"
                class="bg-white rounded-xl border border-slate-200 hover:border-[#0072bc] shadow-xs hover:shadow-md transition p-3.5 flex flex-col justify-between cursor-pointer"
                onclick="window.location.href='{{ route('devices.show', $device->id) }}'">
                
                <div>
                    <!-- Card Header: Name & Status Badge -->
                    <div class="flex items-start justify-between gap-1 pb-2 border-b border-slate-100">
                        <div class="min-w-0">
                            <h4 class="font-bold text-xs text-slate-800 truncate" title="{{ $device->device_name }}">
                                {{ $device->device_name }}
                            </h4>
                            <p class="text-[10px] text-slate-400 truncate mt-0.5">
                                {{ (preg_match('/^BMID/i', $device->location) || preg_match('/^BMID/i', $device->device_name)) ? 'BMID' : ($device->location ?? 'Unassigned') }}
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <span class="card-status-badge px-1.5 py-0.5 rounded text-[9px] font-bold uppercase shrink-0
                            {{ $device->status === 'online' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $device->status === 'warning' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $device->status === 'maintenance' ? 'bg-slate-100 text-slate-700' : '' }}
                            {{ $device->status === 'offline' ? 'bg-rose-100 text-rose-800' : '' }}">
                            {{ $device->status === 'warning' ? 'Warn' : ($device->status === 'maintenance' ? 'Maint' : ucfirst($device->status)) }}
                        </span>
                    </div>

                    <!-- Specs details -->
                    <div class="py-2.5 space-y-1 text-[11px]">
                        <div class="flex justify-between">
                            <span class="text-slate-400">IP:</span>
                            <span class="font-mono font-medium text-slate-700 select-all">{{ $device->ip_address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Latency:</span>
                            <span class="card-latency font-mono font-semibold text-slate-800">
                                {{ $device->response_time !== null ? $device->response_time . ' ms' : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span class="text-slate-400">Last Checked:</span>
                            <span class="card-lastping text-slate-500">
                                {{ $device->last_ping ? \Carbon\Carbon::parse($device->last_ping)->format('H:i:s') : 'Never' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2" onclick="event.stopPropagation()">
                    <a
                        href="{{ route('devices.show', $device->id) }}"
                        class="text-[10px] text-[#0072bc] hover:underline font-semibold">
                        View Detail &rarr;
                    </a>

                    <button
                        type="button"
                        onclick="pingNodeDevice({{ $device->id }})"
                        class="card-ping-btn px-2.5 py-0.5 rounded-md bg-slate-100 hover:bg-[#0072bc] hover:text-white text-slate-700 text-[10px] font-medium transition border border-slate-200">
                        Ping
                    </button>
                </div>

            </div>
            @empty
            <div class="col-span-full bg-white rounded-xl p-8 text-center border border-slate-200 text-slate-400 text-xs">
                No devices found matching query.
            </div>
            @endforelse

        </div>

        <!-- Pagination for Card View -->
        @if($tableDevices->hasPages())
        <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-xs">
            {{ $tableDevices->links() }}
        </div>
        @endif

    </div>

</div>

<!-- BATCH PING SWEEP MODAL (LIGHT MODE) -->
<div id="pingModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-2xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden">
        
        <div class="p-4 bg-[#0072bc] text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xs text-white">Airport LAN Ping Sweep</h3>
                    <p class="text-[10px] text-blue-100">Scanning all FIDS devices across Airport LAN</p>
                </div>
            </div>

            <button
                type="button"
                id="btnModalClose"
                onclick="closePingModal()"
                class="w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="p-5 space-y-3.5">
            
            <!-- Progress Info -->
            <div>
                <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1.5">
                    <span id="pingProgressText">Ready to scan LAN devices</span>
                    <span id="pingProgressPercent" class="font-mono font-bold text-[#0072bc]">0%</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                    <div id="pingProgressBar" class="h-full bg-[#0072bc] rounded-full transition-all duration-200 w-0"></div>
                </div>
            </div>

            <!-- Ping Results Summary in Modal -->
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="text-[9px] uppercase font-bold text-emerald-800 block">Normal (Online)</span>
                    <span id="modalOnlineCount" class="text-base font-bold text-emerald-700 font-mono">0</span>
                </div>
                <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200">
                    <span class="text-[9px] uppercase font-bold text-amber-800 block">Warning Latency</span>
                    <span id="modalWarningCount" class="text-base font-bold text-amber-700 font-mono">0</span>
                </div>
                <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200">
                    <span class="text-[9px] uppercase font-bold text-rose-800 block">Alert (Offline)</span>
                    <span id="modalOfflineCount" class="text-base font-bold text-rose-700 font-mono">0</span>
                </div>
            </div>

            <!-- Live Log Terminal Area -->
            <div class="space-y-1">
                <span class="text-[11px] font-semibold text-slate-600 block">Live ICMP Console:</span>
                <div id="pingConsole" class="h-36 rounded-xl bg-slate-900 text-slate-200 p-3 font-mono text-[10px] overflow-y-auto space-y-0.5 border border-slate-800">
                    <p class="text-slate-500">// Ready. Press "Start Network Ping" to begin scan.</p>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button
                    type="button"
                    id="btnModalCancel"
                    onclick="cancelOrCloseModal()"
                    class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    Close
                </button>

                <button
                    type="button"
                    id="btnRunBatchPing"
                    onclick="runBatchPingExecution()"
                    class="px-4 py-1.5 rounded-lg bg-[#0072bc] hover:bg-[#005b9f] text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                    <svg id="batchPingIcon" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    </svg>
                    <span id="batchPingBtnLabel">Start Network Ping</span>
                </button>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    // Embedded Device Inventory (Direct from Blade)
    const clientDevices = {!! json_encode($devices->values()) !!};

    let isPingRunning = false;
    let cancelPing = false;
    let autoRefreshTimer = null;
    let countdownValue = 45;

    // Sound control state (persisted in localStorage)
    let isSoundMuted = localStorage.getItem('fids_sound_muted') === 'true';

    // Initialize sound UI on load
    function initSoundUI() {
        const soundIcon = document.getElementById('soundIcon');
        const soundLabel = document.getElementById('soundLabel');
        const btnToggle = document.getElementById('btnToggleSound');

        if (isSoundMuted) {
            if (soundIcon) soundIcon.innerText = '🔇';
            if (soundLabel) soundLabel.innerText = 'Muted';
            if (btnToggle) btnToggle.className = 'px-2.5 py-1 rounded-lg border border-slate-300 bg-slate-100 text-slate-500 text-[11px] font-semibold transition flex items-center gap-1.5 shadow-2xs';
        } else {
            if (soundIcon) soundIcon.innerText = '🔊';
            if (soundLabel) soundLabel.innerText = 'Unmuted';
            if (btnToggle) btnToggle.className = 'px-2.5 py-1 rounded-lg border border-[#0072bc] bg-blue-50 text-[#0072bc] text-[11px] font-semibold transition flex items-center gap-1.5 shadow-2xs';
        }
    }

    // Toggle Sound Control (Muted <-> Unmuted)
    function toggleSoundControl() {
        isSoundMuted = !isSoundMuted;
        localStorage.setItem('fids_sound_muted', isSoundMuted ? 'true' : 'false');
        initSoundUI();

        // Rule: When switching from Muted -> Unmuted:
        // Check if there is currently at least ONE offline/alert device. If so, play sound ONCE immediately.
        if (!isSoundMuted) {
            const offlineEl = document.getElementById('legendOffline');
            const offlineCount = parseInt(offlineEl?.innerText || '0', 10);
            if (offlineCount > 0) {
                playAlertChime();
            }
        }
    }

    // Emergency Alarm Siren & Web Buzzer (Continuous 2.2s Multi-Cycle Warning Siren)
    let globalAudioCtx = null;
    function getAudioContext() {
        if (!globalAudioCtx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) {
                globalAudioCtx = new AudioCtx();
            }
        }
        if (globalAudioCtx && globalAudioCtx.state === 'suspended') {
            globalAudioCtx.resume();
        }
        return globalAudioCtx;
    }

    // Auto-unlock audio on any page interaction
    document.addEventListener('click', () => { getAudioContext(); }, { passive: true });
    document.addEventListener('keydown', () => { getAudioContext(); }, { passive: true });

    function playAlertChime() {
        if (isSoundMuted) return;
        if (typeof window.playAlertBuzzer === 'function') {
            window.playAlertBuzzer();
        }
    }

    // View Switcher (Tree View vs Card Grid View)
    function setViewMode(mode) {
        const treeContainer = document.getElementById('treeViewContainer');
        const tableContainer = document.getElementById('tableViewContainer');
        const tabTree = document.getElementById('tabTree');
        const tabTable = document.getElementById('tabTable');
        const viewInput = document.getElementById('viewModeInput');

        viewInput.value = mode;

        if (mode === 'table') {
            treeContainer.classList.add('hidden');
            tableContainer.classList.remove('hidden');

            tabTable.className = 'px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 bg-[#0072bc] text-white shadow-xs';
            tabTree.className = 'px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900';
        } else {
            tableContainer.classList.add('hidden');
            treeContainer.classList.remove('hidden');

            tabTree.className = 'px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 bg-[#0072bc] text-white shadow-xs';
            tabTable.className = 'px-2.5 py-1 rounded-md text-[11px] font-semibold transition flex items-center gap-1.5 text-slate-600 hover:text-slate-900';
        }

        const url = new URL(window.location);
        url.searchParams.set('view', mode);
        window.history.replaceState({}, '', url);
    }

    // Auto-refresh countdown (Rule: NEVER plays sound on auto-refresh)
    function startAutoRefreshCountdown() {
        if (autoRefreshTimer) clearInterval(autoRefreshTimer);
        countdownValue = 45;

        autoRefreshTimer = setInterval(() => {
            if (isPingRunning) return; // Pause countdown while ping sweep is running

            countdownValue--;
            const cdEl = document.getElementById('autoRefreshCountdown');
            if (cdEl) cdEl.innerText = countdownValue;

            if (countdownValue <= 0) {
                countdownValue = 45;
                refreshStatusData();
            }
        }, 1000);
    }

    let lastUnreadCount = null;

    // Refresh status data from backend JSON feed
    async function refreshStatusData() {
        const btn = document.getElementById('btnRefreshStatus');
        const spinner = document.getElementById('refreshSpinner');
        const lastRefresh = document.getElementById('lastRefreshDisplay');

        if (btn) btn.disabled = true;
        spinner?.classList.add('animate-spin');

        try {
            const res = await fetch('/monitoring/data', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.success) {
                updateStatsCards(data.stats);
                updateDeviceNodes(data.devices);
                if (lastRefresh && data.timestamp) {
                    const timePart = data.timestamp.split(' ').pop();
                    lastRefresh.innerText = `[ Jam: ${timePart || data.timestamp} ]`;
                }

                // If new notifications arrived or unread exist on refresh, trigger buzzer sound
                if (data.unread_notifications !== undefined) {
                    if (data.unread_notifications > 0 && (lastUnreadCount === null || data.unread_notifications > lastUnreadCount) && !isSoundMuted) {
                        playAlertChime();
                    }
                    lastUnreadCount = data.unread_notifications;

                    // Update badges
                    const dot = document.getElementById('sidebarNotifDot');
                    const sideBadge = document.getElementById('sidebarNotifBadge');
                    const headBadge = document.getElementById('headerNotifBadge');
                    const hasUnread = data.unread_notifications > 0;
                    const textVal = data.unread_notifications > 99 ? '99+' : data.unread_notifications;

                    if (dot) dot.classList.toggle('hidden', !hasUnread);
                    if (sideBadge) {
                        sideBadge.innerText = textVal;
                        sideBadge.classList.toggle('hidden', !hasUnread);
                    }
                    if (headBadge) {
                        headBadge.innerText = textVal;
                        headBadge.classList.toggle('hidden', !hasUnread);
                    }
                }
            }
        } catch (err) {
            console.error('Refresh error:', err);
        } finally {
            if (btn) btn.disabled = false;
            spinner?.classList.remove('animate-spin');
            countdownValue = 45;
        }
    }

    function updateStatsCards(stats) {
        if (!stats) return;
        const totalEl = document.getElementById('legendTotal');
        const onlineEl = document.getElementById('legendOnline');
        const offlineEl = document.getElementById('legendOffline');
        const warningEl = document.getElementById('legendWarning');
        const maintEl = document.getElementById('legendMaintenance');

        if (totalEl) totalEl.innerText = stats.total ?? 0;
        if (onlineEl) onlineEl.innerText = stats.online ?? 0;
        if (offlineEl) offlineEl.innerText = stats.offline ?? 0;
        if (warningEl) warningEl.innerText = stats.warning ?? 0;
        if (maintEl) maintEl.innerText = stats.maintenance ?? 0;
    }

    function updateDeviceNodes(devices) {
        if (!devices || !Array.isArray(devices)) return;

        devices.forEach(dev => {
            // Update Tree View node
            const node = document.getElementById(`node-device-${dev.id}`);
            if (node) {
                node.setAttribute('data-status', dev.status);

                const svg = node.querySelector('.node-svg');
                if (svg) {
                    svg.className = 'node-svg w-7 h-5 transition-all duration-150';
                    if (dev.status === 'online') {
                        svg.classList.add('text-[#16a34a]', 'drop-shadow-2xs');
                    } else if (dev.status === 'warning') {
                        svg.classList.add('text-[#d97706]');
                    } else if (dev.status === 'maintenance') {
                        svg.classList.add('text-[#64748b]');
                    } else {
                        svg.classList.add('text-[#dc2626]', 'animate-pulse');
                    }
                }

                const latencyEl = node.querySelector('.node-tooltip-latency');
                if (latencyEl) {
                    latencyEl.innerText = dev.response_time !== null ? `${dev.response_time} ms` : '-';
                }

                const badgeEl = node.querySelector('.node-tooltip-badge');
                if (badgeEl) {
                    badgeEl.innerText = dev.status;
                    badgeEl.className = 'node-tooltip-badge px-1.5 py-0.2 rounded text-[8px] font-bold uppercase ' + 
                        (dev.status === 'online' ? 'bg-emerald-900 text-emerald-300' : 
                        (dev.status === 'warning' ? 'bg-amber-900 text-amber-300' : 
                        (dev.status === 'maintenance' ? 'bg-slate-800 text-slate-300' : 'bg-rose-900 text-rose-300')));
                }

                const checkedEl = node.querySelector('.node-tooltip-checked');
                if (checkedEl && dev.last_ping) {
                    checkedEl.innerText = dev.last_ping.split(' ').pop();
                }
            }

            // Update Card View node
            const card = document.getElementById(`card-device-${dev.id}`);
            if (card) {
                const cardBadge = card.querySelector('.card-status-badge');
                if (cardBadge) {
                    cardBadge.innerText = dev.status === 'warning' ? 'Warn' : (dev.status === 'maintenance' ? 'Maint' : dev.status.charAt(0).toUpperCase() + dev.status.slice(1));
                    cardBadge.className = 'card-status-badge px-1.5 py-0.5 rounded text-[9px] font-bold uppercase shrink-0 ' +
                        (dev.status === 'online' ? 'bg-emerald-100 text-emerald-800' :
                        (dev.status === 'warning' ? 'bg-amber-100 text-amber-800' :
                        (dev.status === 'maintenance' ? 'bg-slate-100 text-slate-700' : 'bg-rose-100 text-rose-800')));
                }

                const cardLatency = card.querySelector('.card-latency');
                if (cardLatency) {
                    cardLatency.innerText = dev.response_time !== null ? `${dev.response_time} ms` : '-';
                }

                const cardLastPing = card.querySelector('.card-lastping');
                if (cardLastPing && dev.last_ping) {
                    cardLastPing.innerText = dev.last_ping.includes(' ') ? dev.last_ping.split(' ').pop() : dev.last_ping;
                }
            }
        });
    }

    // Ping single device on click ⚡
    async function pingNodeDevice(deviceId) {
        const node = document.getElementById(`node-device-${deviceId}`);
        const card = document.getElementById(`card-device-${deviceId}`);
        const svg = node?.querySelector('.node-svg');
        const cardBtn = card?.querySelector('.card-ping-btn');

        if (svg) svg.classList.add('opacity-50', 'animate-spin');
        if (cardBtn) {
            cardBtn.disabled = true;
            cardBtn.innerText = '...';
        }

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
                updateDeviceNodes([data.data]);
                refreshStatusData();
                if (data.data.status === 'offline') {
                    if (typeof window.playAlertBuzzer === 'function') {
                        window.playAlertBuzzer();
                    }
                    if (typeof window.showFloatingNotificationToast === 'function') {
                        window.showFloatingNotificationToast({
                            type: 'offline',
                            message: `Perangkat '${data.data.device_name}' (${data.data.ip_address}) pada lokasi '${data.data.location || 'Airport'}' terdeteksi OFFLINE / Terputus.`,
                            created_at: 'Just now'
                        }, false);
                    }
                } else if (data.data.status === 'warning') {
                    if (typeof window.playAlertBuzzer === 'function') {
                        window.playAlertBuzzer();
                    }
                    if (typeof window.showFloatingNotificationToast === 'function') {
                        window.showFloatingNotificationToast({
                            type: 'warning',
                            message: `Perangkat '${data.data.device_name}' (${data.data.ip_address}) mengalami lonjakan latency tinggi (${data.data.response_time} ms).`,
                            created_at: 'Just now'
                        }, false);
                    }
                }
            }
        } catch (err) {
            console.error('Node ping error:', err);
        } finally {
            if (svg) svg.classList.remove('opacity-50', 'animate-spin');
            if (cardBtn) {
                cardBtn.disabled = false;
                cardBtn.innerText = 'Ping';
            }
        }
    }

    // Modal controls
    function openPingModal() {
        const audioEl = document.getElementById('fidsGlobalAudio');
        if (audioEl) audioEl.load();
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (AudioCtx && !window._audioCtx) window._audioCtx = new AudioCtx();
        if (window._audioCtx && window._audioCtx.state === 'suspended') window._audioCtx.resume();
        document.getElementById('pingModal').classList.remove('hidden');
    }

    function closePingModal() {
        if (isPingRunning) {
            if (!confirm('Ping sweep is currently in progress. Stop and close?')) return;
            cancelPing = true;
        }
        document.getElementById('pingModal').classList.add('hidden');
    }

    function cancelOrCloseModal() {
        closePingModal();
    }

    // BATCH PING SWEEP (Ultra-responsive OS-level Parallel Pings)
    async function runBatchPingExecution() {
        if (isPingRunning) return;

        const audioEl = document.getElementById('fidsGlobalAudio');
        if (audioEl) audioEl.load();
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (AudioCtx && !window._audioCtx) window._audioCtx = new AudioCtx();
        if (window._audioCtx && window._audioCtx.state === 'suspended') window._audioCtx.resume();

        isPingRunning = true;
        cancelPing = false;

        const btn = document.getElementById('btnRunBatchPing');
        const icon = document.getElementById('batchPingIcon');
        const label = document.getElementById('batchPingBtnLabel');
        const pBar = document.getElementById('pingProgressBar');
        const pText = document.getElementById('pingProgressText');
        const pPercent = document.getElementById('pingProgressPercent');
        const consoleBox = document.getElementById('pingConsole');

        const mOnline = document.getElementById('modalOnlineCount');
        const mWarning = document.getElementById('modalWarningCount');
        const mOffline = document.getElementById('modalOfflineCount');

        let onlineCount = 0;
        let warningCount = 0;
        let offlineCount = 0;

        mOnline.innerText = '0';
        mWarning.innerText = '0';
        mOffline.innerText = '0';

        btn.disabled = true;
        label.innerText = 'Scanning Network...';
        icon.classList.add('animate-spin');
        consoleBox.innerHTML = '<p class="text-blue-400 font-bold">[START] Sending parallel ICMP ping requests to Airport LAN...</p>';

        try {
            let allDevices = clientDevices;

            if (!allDevices || allDevices.length === 0) {
                const dataRes = await fetch('/monitoring/data', { headers: { 'Accept': 'application/json' } });
                const dataObj = await dataRes.json();
                allDevices = dataObj.devices || [];
            }

            const total = allDevices.length;

            if (total === 0) {
                consoleBox.innerHTML += '<p class="text-rose-400">[ERROR] No devices found in database.</p>';
                return;
            }

            consoleBox.innerHTML += `<p class="text-slate-300">[INFO] Loaded ${total} devices. Executing high-speed parallel ICMP ping sweep...</p>`;

            const chunkSize = 25;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            let processed = 0;

            for (let i = 0; i < total; i += chunkSize) {
                if (cancelPing) {
                    consoleBox.innerHTML += `<p class="text-amber-400">[CANCELLED] Ping sweep cancelled by user.</p>`;
                    break;
                }

                const chunk = allDevices.slice(i, i + chunkSize);
                const chunkIds = chunk.map(d => d.id);

                // Highlight nodes currently being scanned with active radar glow
                chunk.forEach(dev => {
                    const node = document.getElementById(`node-device-${dev.id}`);
                    if (node) {
                        const svg = node.querySelector('.node-svg');
                        if (svg) svg.classList.add('opacity-70', 'animate-pulse');
                    }
                });

                const res = await fetch('/monitoring/ping-batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        device_ids: chunkIds,
                        timeout: 300
                    })
                });

                const chunkData = await res.json();

                if (chunkData.success && chunkData.results) {
                    chunkData.results.forEach(resItem => {
                        processed++;
                        if (resItem.status === 'online') {
                            onlineCount++;
                            consoleBox.innerHTML += `<p class="text-emerald-400">[ONLINE] ${resItem.device_name} (${resItem.ip_address}) - ${resItem.response_time}ms</p>`;
                        } else if (resItem.status === 'warning') {
                            warningCount++;
                            consoleBox.innerHTML += `<p class="text-amber-400">[WARN] ${resItem.device_name} (${resItem.ip_address}) - Latency ${resItem.response_time}ms</p>`;
                        } else if (resItem.status === 'maintenance') {
                            consoleBox.innerHTML += `<p class="text-slate-400">[MAINT] ${resItem.device_name} (${resItem.ip_address}) - Maintenance mode</p>`;
                        } else {
                            offlineCount++;
                            consoleBox.innerHTML += `<p class="text-rose-400">[DOWN] ${resItem.device_name} (${resItem.ip_address}) - Timed out</p>`;
                        }
                    });

                    updateDeviceNodes(chunkData.results);
                    if (chunkData.stats) {
                        updateStatsCards(chunkData.stats);
                    }
                }

                mOnline.innerText = onlineCount;
                mWarning.innerText = warningCount;
                mOffline.innerText = offlineCount;

                const percent = Math.min(100, Math.round((processed / total) * 100));
                pBar.style.width = `${percent}%`;
                pPercent.innerText = `${percent}%`;
                pText.innerText = `Scanned ${processed} of ${total} devices (${percent}%)`;
                consoleBox.scrollTop = consoleBox.scrollHeight;
            }

            consoleBox.innerHTML += `<p class="text-emerald-400 font-bold mt-1">[COMPLETE] Scan finished: ${onlineCount} Online, ${warningCount} Warning, ${offlineCount} Offline.</p>`;
            consoleBox.scrollTop = consoleBox.scrollHeight;

            // RULE: When scan finishes, if one or more devices are Offline/Alert (or Warning) -> Play alert sound & show floating notification toast
            if (offlineCount > 0 || warningCount > 0) {
                consoleBox.innerHTML += `<p class="text-rose-400 font-bold">[ALERT] ${offlineCount} offline device(s) detected. Playing alert buzzer sound...</p>`;
                if (typeof window.playAlertBuzzer === 'function') {
                    window.playAlertBuzzer();
                }
                if (typeof window.showFloatingNotificationToast === 'function') {
                    window.showFloatingNotificationToast({
                        type: 'offline',
                        message: `Hasil Ping Sweep: Terdeteksi ${offlineCount} perangkat OFFLINE / Terputus dari jaringan Airport LAN.`,
                        created_at: 'Just now'
                    }, false);
                }
            }

            refreshStatusData();

        } catch (err) {
            console.error('Batch ping error:', err);
            consoleBox.innerHTML += `<p class="text-rose-500 font-bold">[ERROR] ${err.message || 'Execution error'}</p>`;
        } finally {
            isPingRunning = false;
            btn.disabled = false;
            label.innerText = 'Run Scan Again';
            icon.classList.remove('animate-spin');
        }
    }

    function startLiveClock() {
        const lastRefresh = document.getElementById('lastRefreshDisplay');
        function updateClock() {
            if (lastRefresh) {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                lastRefresh.innerText = `[ Jam: ${hours}:${minutes}:${seconds} ]`;
            }
        }
        updateClock();
        setInterval(updateClock, 1000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSoundUI();
        startLiveClock();
        startAutoRefreshCountdown();
    });
</script>
@endpush

@endsection