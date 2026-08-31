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
    </style>
</head>

<body>

<div class="flex h-screen overflow-hidden bg-slate-50">

    <!-- Light Mode Sidebar (Angkasa Pura Theme) -->
    <aside
        id="sidebar"
        class="sidebar w-56 bg-white text-slate-800 flex flex-col fixed left-0 top-0 h-screen shadow-xs z-50 border-r border-slate-200">

        <!-- Logo Section -->
        <div class="h-16 px-3.5 flex items-center justify-between border-b border-slate-200/80 bg-white">
            <div class="logo-container flex flex-col justify-center overflow-hidden">
                <img src="{{ asset('images/angkasapura-logo.jpg') }}" alt="Angkasa Pura | Airports" class="h-7 w-auto max-w-[155px] object-contain object-left">
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
                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-rose-500"></span>
                    @endif
                </div>
                <span class="menu-text text-xs flex-1 flex items-center justify-between">
                    <span>Notifications</span>
                    @if($unreadNotifications > 0)
                        <span class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-rose-600 text-white">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
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
                    class="relative w-8 h-8 rounded-lg hover:bg-slate-100 transition flex items-center justify-center border border-slate-200 text-slate-600 hover:text-slate-900"
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

                    @if($unreadNotifications > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-0.5 bg-rose-600 rounded-full text-white text-[9px] flex items-center justify-center font-bold shadow-xs">
                            {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                        </span>
                    @endif
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('toggleSidebar');
        const main = document.getElementById('mainContent');
        const header = document.getElementById('header');
        const html = document.documentElement;

        // Terapkan class jika sudah tersimpan
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            main?.classList.add('collapsed-margin');
            header?.classList.add('collapsed-left');
        }

        // Event Toggle Click
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
    });
</script>

@stack('scripts')

</body>
</html>