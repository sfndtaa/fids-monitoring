@php
use App\Models\DeviceNotification;

$unreadNotifications = DeviceNotification::where('is_read', false)->count();
$currentUser = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Angkasa Pura Airports - FIDS Monitoring</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Anti-flicker script for sidebar collapsed state -->
    <script>
        (function() {
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                document.documentElement.classList.add('sidebar-is-collapsed');
            }
        })();
    </script>

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace, ui-monospace;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            overflow: hidden;
        }

        /* Sidebar transition */
        .sidebar {
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Collapsed states */
        html.sidebar-is-collapsed #sidebar,
        .sidebar.collapsed {
            width: 64px !important;
        }

        html.sidebar-is-collapsed #mainContent,
        #mainContent.collapsed-margin {
            margin-left: 64px !important;
        }

        html.sidebar-is-collapsed #header,
        #header.collapsed-left {
            left: 64px !important;
        }

        /* Smooth text hiding */
        .sidebar .menu-text,
        .sidebar .logo-container,
        .sidebar .user-info {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease, max-width 0.25s ease;
            max-width: 170px;
            opacity: 1;
        }

        html.sidebar-is-collapsed .sidebar .menu-text,
        html.sidebar-is-collapsed .sidebar .logo-container,
        html.sidebar-is-collapsed .sidebar .user-info,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logo-container,
        .sidebar.collapsed .user-info {
            max-width: 0;
            opacity: 0;
            pointer-events: none;
        }

        html.sidebar-is-collapsed .sidebar .menu-item,
        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }

        /* Global Top Loading Bar */
        #globalTopBar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #0072bc, #38bdf8, #0072bc);
            background-size: 200% 100%;
            animation: topBarShimmer 1.5s infinite linear;
            z-index: 999999;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 114, 188, 0.6);
            opacity: 0;
            pointer-events: none;
        }

        @keyframes topBarShimmer {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
    </style>
</head>

<body>

<!-- Global Top Loading Progress Bar -->
<div id="globalTopBar"></div>

<div class="flex h-screen overflow-hidden bg-slate-50">

    <!-- Light Mode Sidebar (Angkasa Pura Theme) -->
    <aside
        id="sidebar"
        class="sidebar w-56 bg-white text-slate-800 flex flex-col fixed left-0 top-0 h-screen shadow-xs z-50 border-r border-slate-200">

        <!-- Logo Section -->
        <div class="h-16 px-3.5 flex items-center justify-between border-b border-slate-200/80 bg-white">
            <div class="logo-container flex flex-col justify-center overflow-hidden">
                <img src="{{ asset('images/injourney-logo.png') }}" alt="InJourney Airports" class="h-8 w-auto max-w-[155px] object-contain object-left">
                <p class="text-[10px] font-bold text-[#0072bc] tracking-wider uppercase mt-0.5">
                    FIDS Monitoring
                </p>
            </div>

            <button
                id="toggleSidebar"
                type="button"
                tabindex="-1"
                class="p-1.5 rounded-lg hover:bg-slate-100 transition text-slate-500 hover:text-slate-800 shrink-0"
                title="Toggle Sidebar">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Menu Navigation -->
        <nav class="flex-1 px-2.5 py-3 space-y-1 overflow-y-auto overflow-x-hidden">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('dashboard') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z"/>
                </svg>
                <span class="menu-text text-xs">Dashboard</span>
            </a>

            <!-- Devices -->
            <a href="{{ route('devices') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('devices*') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M4 5h16v10H4V5Z"/>
                </svg>
                <span class="menu-text text-xs">Devices</span>
            </a>

            <!-- Monitoring -->
            <a href="{{ route('monitoring') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('monitoring') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h3l2-5 4 10 2-5h3"/>
                </svg>
                <span class="menu-text text-xs">Monitoring</span>
            </a>

            <!-- History -->
            <a href="{{ route('history') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('history') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M21 12A9 9 0 1112 3"/>
                </svg>
                <span class="menu-text text-xs">History</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('notifications') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <div class="relative shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>
                    </svg>
                    <span id="sidebarNotifDot" class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-rose-500 {{ $unreadNotifications > 0 ? '' : 'hidden' }}"></span>
                </div>
                <span class="menu-text text-xs flex-1 flex items-center justify-between">
                    <span>Notifications</span>
                    <span id="sidebarNotifBadge" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-rose-600 text-white {{ $unreadNotifications > 0 ? '' : 'hidden' }}">
                        {{ $unreadNotifications }}
                    </span>
                </span>
            </a>

            <!-- Import -->
            <a href="{{ route('import.index') }}"
                class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                {{ request()->routeIs('import*') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="menu-text text-xs">Import</span>
            </a>

            <!-- User Management (Admin Only) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="pt-2 mt-2 border-t border-slate-200">
                <p class="menu-text text-[10px] uppercase font-bold text-slate-400 px-2.5 mb-1 tracking-wider">
                    Admin
                </p>
                <a href="{{ route('users.index') }}"
                    class="menu-item flex items-center gap-2.5 rounded-lg px-2.5 py-2 transition-colors
                    {{ request()->routeIs('users*') ? 'bg-[#0072bc] text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-[#0072bc]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="menu-text text-xs">User Management</span>
                </a>
            </div>
            @endif

        </nav>

        <!-- User Profile & Logout -->
        <div class="p-2.5 border-t border-slate-200 space-y-1.5 bg-slate-50/60">
            @if($currentUser)
            <div class="flex items-center gap-2 px-1.5 py-1 text-slate-700">
                <div class="w-7 h-7 rounded-md bg-[#0072bc] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                    {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}
                </div>
                <div class="user-info truncate">
                    <p class="text-[11px] font-semibold text-slate-900 truncate leading-tight">{{ $currentUser->name }}</p>
                    <span class="text-[9px] uppercase font-bold text-[#0072bc]">
                        {{ $currentUser->role ?? 'User' }}
                    </span>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item w-full flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6"/>
                    </svg>
                    <span class="menu-text text-xs font-medium">Logout</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div id="mainContent" class="flex-1 ml-56 transition-all duration-250 ease-in-out flex flex-col h-screen overflow-hidden bg-slate-50">

        <!-- Header -->
        <header id="header" class="bg-white h-16 shadow-2xs flex items-center justify-between px-6 fixed top-0 left-56 right-0 z-40 transition-all duration-250 ease-in-out border-b border-slate-200">
            <div>
                <h2 class="text-sm font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    @if(request()->routeIs('dashboard'))
                        Dashboard Overview
                    @elseif(request()->routeIs('devices.show'))
                        Device Details
                    @elseif(request()->routeIs('devices*'))
                        Device Inventory
                    @elseif(request()->routeIs('monitoring'))
                        FIDS Network Infrastructure Monitoring
                    @elseif(request()->routeIs('history'))
                        Monitoring Logs History
                    @elseif(request()->routeIs('notifications'))
                        Alerts & Notifications
                    @elseif(request()->routeIs('users*'))
                        User Management
                    @elseif(request()->routeIs('import*'))
                        Import Data
                    @else
                        FIDS Monitoring
                    @endif
                </h2>
                <p class="text-slate-400 text-[10px]">
                    Angkasa Pura Airports • SAMS Sepinggan International Airport
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Icon Notifikasi -->
                <a href="{{ route('notifications') }}"
                    id="headerNotifBtn"
                    class="relative w-8 h-8 rounded-lg hover:bg-slate-100 transition flex items-center justify-center border border-slate-200 text-slate-600 hover:text-slate-900 cursor-pointer"
                    title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>
                    </svg>

                    <span id="headerNotifBadge" class="absolute -top-1 -right-1 min-w-[16px] h-4 px-0.5 bg-rose-600 rounded-full text-white text-[9px] flex items-center justify-center font-bold shadow-xs {{ $unreadNotifications > 0 ? '' : 'hidden' }}">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </span>
                </a>

                <!-- Profile Card -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-[#0072bc] text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-semibold text-slate-800 leading-tight">
                            {{ $currentUser->name ?? 'User' }}
                        </p>
                        <p class="text-[10px] text-slate-400 capitalize">
                            {{ $currentUser->role ?? 'User' }}
                        </p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="content mt-16 px-5 py-4">
            @yield('content')
        </main>

    </div>

</div>

<!-- Global Audio Element Fallback -->
<audio id="fidsGlobalAudio" src="{{ asset('sounds/alarm-buzzer.wav') }}" preload="auto"></audio>

<script>
    (function() {
        // Global Audio Context & State
        window._audioCtx = null;
        window._pendingBuzzer = false;
        window._audioUnlocked = false;

        // Emergency Siren Synthesizer Function (2.2s multi-cycle sawtooth & square tone)
        function playSirenSynth(ctx) {
            try {
                const now = ctx.currentTime;
                const totalDuration = 2.2;

                const osc1 = ctx.createOscillator();
                const osc2 = ctx.createOscillator();
                const osc3 = ctx.createOscillator();
                
                const filter = ctx.createBiquadFilter();
                filter.type = 'lowpass';
                filter.frequency.setValueAtTime(3600, now);
                filter.Q.setValueAtTime(3.5, now);

                const gainNode = ctx.createGain();

                osc1.type = 'sawtooth';
                osc2.type = 'square';
                osc3.type = 'sawtooth';

                // Cycle 1
                osc1.frequency.setValueAtTime(520, now);
                osc1.frequency.exponentialRampToValueAtTime(1450, now + 0.28);
                osc1.frequency.exponentialRampToValueAtTime(600, now + 0.55);
                osc2.frequency.setValueAtTime(520, now);
                osc2.frequency.exponentialRampToValueAtTime(1450, now + 0.28);
                osc2.frequency.exponentialRampToValueAtTime(600, now + 0.55);

                // Cycle 2
                osc1.frequency.exponentialRampToValueAtTime(1500, now + 0.83);
                osc1.frequency.exponentialRampToValueAtTime(620, now + 1.10);
                osc2.frequency.exponentialRampToValueAtTime(1500, now + 0.83);
                osc2.frequency.exponentialRampToValueAtTime(620, now + 1.10);

                // Cycle 3
                osc1.frequency.exponentialRampToValueAtTime(1550, now + 1.38);
                osc1.frequency.exponentialRampToValueAtTime(640, now + 1.65);
                osc2.frequency.exponentialRampToValueAtTime(1550, now + 1.38);
                osc2.frequency.exponentialRampToValueAtTime(640, now + 1.65);

                // Cycle 4
                osc1.frequency.exponentialRampToValueAtTime(1580, now + 1.93);
                osc1.frequency.exponentialRampToValueAtTime(500, now + 2.20);
                osc2.frequency.exponentialRampToValueAtTime(1580, now + 1.93);
                osc2.frequency.exponentialRampToValueAtTime(500, now + 2.20);

                // 2nd Harmonic Layer
                osc3.frequency.setValueAtTime(1040, now);
                osc3.frequency.exponentialRampToValueAtTime(2900, now + 0.28);
                osc3.frequency.exponentialRampToValueAtTime(1200, now + 0.55);
                osc3.frequency.exponentialRampToValueAtTime(3000, now + 0.83);
                osc3.frequency.exponentialRampToValueAtTime(1240, now + 1.10);
                osc3.frequency.exponentialRampToValueAtTime(3100, now + 1.38);
                osc3.frequency.exponentialRampToValueAtTime(1280, now + 1.65);
                osc3.frequency.exponentialRampToValueAtTime(3160, now + 1.93);
                osc3.frequency.exponentialRampToValueAtTime(1000, now + 2.20);

                gainNode.gain.setValueAtTime(0.01, now);
                gainNode.gain.linearRampToValueAtTime(0.98, now + 0.04);
                gainNode.gain.setValueAtTime(0.98, now + 2.05);
                gainNode.gain.exponentialRampToValueAtTime(0.001, now + totalDuration);

                osc1.connect(filter);
                osc2.connect(filter);
                osc3.connect(filter);
                filter.connect(gainNode);
                gainNode.connect(ctx.destination);

                osc1.start(now);
                osc2.start(now);
                osc3.start(now);
                osc1.stop(now + totalDuration);
                osc2.stop(now + totalDuration);
                osc3.stop(now + totalDuration);
            } catch(e) {
                console.warn('Synth error:', e);
            }
        }

        // Global Alert Siren Buzzer Trigger
        window.playAlertBuzzer = function() {
            // 1. Play HTML5 Audio element
            const audioEl = document.getElementById('fidsGlobalAudio');
            if (audioEl) {
                audioEl.currentTime = 0;
                const p = audioEl.play();
                if (p !== undefined) {
                    p.then(() => {
                        window._audioUnlocked = true;
                    }).catch(() => {
                        window._pendingBuzzer = true;
                    });
                }
            }

            // 2. Play Web Audio API Synthesis
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (AudioCtx) {
                    if (!window._audioCtx) {
                        window._audioCtx = new AudioCtx();
                    }
                    const ctx = window._audioCtx;
                    if (ctx.state === 'suspended') {
                        ctx.resume().then(() => {
                            playSirenSynth(ctx);
                            window._audioUnlocked = true;
                        }).catch(() => {
                            window._pendingBuzzer = true;
                        });
                    } else {
                        playSirenSynth(ctx);
                    }
                }
            } catch(e) {
                console.warn('Web Audio error:', e);
            }
        };

        // User Gesture Auto-Unlock Handler
        function unlockAudio() {
            if (window._audioCtx && window._audioCtx.state === 'suspended') {
                window._audioCtx.resume().catch(() => {});
            }
            const audioEl = document.getElementById('fidsGlobalAudio');
            if (audioEl && !window._audioUnlocked) {
                audioEl.muted = true;
                const p = audioEl.play();
                if (p !== undefined) {
                    p.then(() => {
                        audioEl.pause();
                        audioEl.currentTime = 0;
                        audioEl.muted = false;
                        window._audioUnlocked = true;
                        if (window._pendingBuzzer) {
                            window._pendingBuzzer = false;
                            window.playAlertBuzzer();
                        }
                    }).catch(() => {});
                }
            } else if (window._pendingBuzzer) {
                window._pendingBuzzer = false;
                window.playAlertBuzzer();
            }
        }

        ['click', 'keydown', 'touchstart', 'mousedown', 'pointerdown'].forEach(evt => {
            document.addEventListener(evt, unlockAudio, { passive: true });
        });
    })();

    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('toggleSidebar');
        const main = document.getElementById('mainContent');
        const header = document.getElementById('header');
        const html = document.documentElement;

        // Apply saved sidebar collapsed state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            main?.classList.add('collapsed-margin');
            header?.classList.add('collapsed-left');
        }

        // Toggle Sidebar click event
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                html.classList.remove('sidebar-is-collapsed');
                const isCollapsed = sidebar.classList.toggle('collapsed');

                if (isCollapsed) {
                    main.classList.add('collapsed-margin');
                    header.classList.add('collapsed-left');
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    main.classList.remove('collapsed-margin');
                    header.classList.remove('collapsed-left');
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });
        }

        // ========================================================
        // COMPACT TOAST NOTIFICATION & AUDIO REPEAT CONTROLLER
        // ========================================================
        window._maintenanceInterval = null;

        window.startMaintenanceSoundLoop = function() {
            window.stopMaintenanceSoundLoop();
            
            // Play alarm immediately (~2.2s)
            if (typeof window.playAlertBuzzer === 'function') {
                window.playAlertBuzzer();
            }

            // Pattern: Alarm (2.2s) -> 5s silence pause -> Alarm (2.2s) -> 5s silence ...
            // Total interval = 2200ms + 5000ms = 7200ms
            window._maintenanceInterval = setInterval(() => {
                if (typeof window.playAlertBuzzer === 'function') {
                    window.playAlertBuzzer();
                }
            }, 7200);
        };

        window.stopMaintenanceSoundLoop = function() {
            if (window._maintenanceInterval) {
                clearInterval(window._maintenanceInterval);
                window._maintenanceInterval = null;
            }
        };

        window.closeToast = function(toastEl) {
            if (!toastEl) return;
            toastEl.classList.add('opacity-0', '-translate-y-2');
            
            // If this was an active maintenance toast, stop repeating sound!
            window.stopMaintenanceSoundLoop();
            
            setTimeout(() => {
                toastEl.remove();
            }, 300);
        };

        // Floating Compact Horizontal Toast Generator (Matching User's Image 3)
        window.showFloatingNotificationToast = function(notif, isMaintenanceAlert = false) {
            const container = document.getElementById('globalToastContainer');
            if (!container) return;

            // Prevent duplicate toasts for the same event
            const existingId = notif.id ? `toast-notif-${notif.id}` : (isMaintenanceAlert ? 'toast-maint-active' : 'toast-maint-completed');
            const existing = document.getElementById(existingId);
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = existingId;
            toast.className = 'pointer-events-auto w-full max-w-lg rounded-2xl border border-slate-200/90 bg-white p-3.5 sm:p-4 shadow-xl flex items-start gap-3.5 transition-all duration-300 transform -translate-y-2 opacity-0 cursor-pointer';

            const type = (notif.type || '').toLowerCase();
            const isOffline = type === 'offline' || type === 'alert' || type === 'down';
            const isMaint = type === 'maintenance' || isMaintenanceAlert;
            const isWarn = type === 'warning';
            const isOnline = type === 'online' || type === 'maintenance_completed' || (!isOffline && !isMaint && !isWarn && !isMaintenanceAlert);

            let iconBg, icon, title, titleColor, borderClass;

            if (isOffline) {
                // RED for Offline / Inactive FIDS
                iconBg = 'bg-rose-100 text-rose-700 border border-rose-200';
                icon = '🚨';
                title = 'DEVICE OFFLINE / TERPUTUS';
                titleColor = 'text-rose-700';
                borderClass = 'border-rose-200 bg-white';
            } else if (isMaint) {
                // YELLOW / AMBER for Maintenance
                iconBg = 'bg-amber-100 text-amber-800 border border-amber-300';
                icon = '🛠️';
                title = 'MAINTENANCE ALERT';
                titleColor = 'text-amber-800';
                borderClass = 'border-amber-200 bg-white';
            } else if (isWarn) {
                // ORANGE / AMBER for Latency Warning
                iconBg = 'bg-orange-100 text-orange-800 border border-orange-200';
                icon = '⚠️';
                title = 'LATENCY WARNING';
                titleColor = 'text-orange-800';
                borderClass = 'border-orange-200 bg-white';
            } else {
                // GREEN for Maintenance Selesai / Online
                iconBg = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                icon = '✅';
                title = 'MAINTENANCE SELESAI';
                titleColor = 'text-emerald-800';
                borderClass = 'border-emerald-200 bg-white';
            }

            const timeStr = notif.created_at ? (notif.created_at.includes(' ') ? notif.created_at.split(' ').pop() : notif.created_at) : 'Just now';

            toast.innerHTML = `
                <div class="w-10 h-10 rounded-xl ${iconBg} flex items-center justify-center shrink-0 text-base shadow-2xs font-bold">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0" onclick="window.stopMaintenanceSoundLoop(); window.location.href='{{ route('notifications') }}'">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider ${titleColor}">
                            ${title}
                        </span>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="text-[10px] text-slate-400 font-mono">${timeStr}</span>
                            <button type="button" onclick="event.stopPropagation(); window.closeToast(this.closest('[id^=toast-]'))" class="text-slate-400 hover:text-slate-700 text-sm font-bold leading-none p-0.5 rounded hover:bg-slate-100 transition" title="Tutup Notifikasi">&times;</button>
                        </div>
                    </div>
                    <p class="text-xs text-slate-700 mt-1 leading-snug font-medium line-clamp-2">${notif.message}</p>
                </div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('-translate-y-2', 'opacity-0');
            });

            // If it's not a repeating maintenance alert, auto-dismiss after 6 seconds
            if (!isMaintenanceAlert) {
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        toast.classList.add('opacity-0', '-translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 6000);
            }
        };

        function updateNotifBadges(count) {
            const dot = document.getElementById('sidebarNotifDot');
            const sideBadge = document.getElementById('sidebarNotifBadge');
            const headBadge = document.getElementById('headerNotifBadge');

            const hasUnread = count > 0;
            const textVal = count > 99 ? '99+' : count;

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

        // Live Global Notification Poller (Background Check every 5s - Silent Updates)
        let lastGlobalNotifCount = @json($unreadNotifications);
        let lastGlobalNotifId = null;

        async function pollGlobalNotifications() {
            try {
                const res = await fetch('{{ route("notifications.unread") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();

                if (data && data.unread_count !== undefined) {
                    updateNotifBadges(data.unread_count);

                    // Show toast notification for new notifications (silent toast without repeating alarm)
                    if (data.unread_count > 0 && (data.unread_count > lastGlobalNotifCount || (data.latest && data.latest.id !== lastGlobalNotifId))) {
                        if (data.latest && data.latest.message) {
                            window.showFloatingNotificationToast(data.latest, false);
                        }
                    }

                    lastGlobalNotifCount = data.unread_count;
                    if (data.latest) lastGlobalNotifId = data.latest.id;
                }
            } catch (e) {
                // Silent catch
            }
        }

        // Initialize background polling every 5 seconds
        setInterval(pollGlobalNotifications, 5000);

        // ==========================================
        // MAINTENANCE EVENT TRIGGER FROM BACKEND FLASH
        // ==========================================
        @if(session('maintenance_action') === 'entered')
            setTimeout(() => {
                const msg = @json(session('maintenance_msg') ?? session('success') ?? 'Perangkat telah dialihkan ke status MAINTENANCE.');
                window.showFloatingNotificationToast({
                    type: 'maintenance',
                    message: msg,
                    created_at: 'Just now'
                }, true);
                window.startMaintenanceSoundLoop();
            }, 300);
        @elseif(session('maintenance_action') === 'completed')
            setTimeout(() => {
                window.stopMaintenanceSoundLoop();
                const msg = @json(session('maintenance_msg') ?? session('success') ?? 'Perangkat telah selesai pemeliharaan dan kembali normal.');
                window.showFloatingNotificationToast({
                    type: 'online',
                    message: msg,
                    created_at: 'Just now'
                }, false);
                if (typeof window.playAlertBuzzer === 'function') {
                    window.playAlertBuzzer(); // Play single sound once
                }
            }, 300);
        @endif

        // ==========================================
        // GLOBAL TOP LOADING PROGRESS BAR CONTROLLER
        // ==========================================
        window.topBarLoading = {
            bar: document.getElementById('globalTopBar'),
            timer: null,
            start() {
                if (!this.bar) this.bar = document.getElementById('globalTopBar');
                if (!this.bar) return;
                clearInterval(this.timer);
                this.bar.style.opacity = '1';
                this.bar.style.width = '25%';
                let progress = 25;
                this.timer = setInterval(() => {
                    if (progress < 85) {
                        progress += (85 - progress) * 0.15;
                        this.bar.style.width = progress + '%';
                    }
                }, 120);
            },
            done() {
                if (!this.bar) this.bar = document.getElementById('globalTopBar');
                if (!this.bar) return;
                clearInterval(this.timer);
                this.bar.style.width = '100%';
                setTimeout(() => {
                    this.bar.style.opacity = '0';
                    setTimeout(() => {
                        this.bar.style.width = '0%';
                    }, 250);
                }, 180);
            }
        };

        // Attach top bar loading on internal link navigations & form submits
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && !link.hasAttribute('download') && 
                !link.href.startsWith('javascript:') && !link.href.includes('#') && 
                link.origin === window.location.origin) {
                window.topBarLoading.start();
            }
        });

        document.addEventListener('submit', (e) => {
            window.topBarLoading.start();
        });
    });
</script>

<!-- Global Toast Alert Container (Top-Right Floating Horizontal Banners) -->
<div id="globalToastContainer" class="fixed top-4 right-4 sm:right-6 z-[99999] space-y-2.5 pointer-events-none flex flex-col items-end max-w-lg w-full"></div>

<!-- Global Preloaded Alert Audio Element -->
<audio id="fidsGlobalAudio" src="{{ asset('sounds/alarm-buzzer.wav') }}" preload="auto"></audio>

@stack('scripts')

</body>
</html>