@php
use App\Models\DeviceNotification;

$unreadNotifications = DeviceNotification::where('is_read', false)->count();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport Flight Information Display Monitoring</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SCRIPT ANTI-FLICKER (Ditaruh di Head agar dieksekusi sebelum page render) -->
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
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #F1F5F9;
            overflow: hidden;
        }

        /* Transition halus */
        .sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* State Collapsed via Class HTML Root (Instant Load) & Class Sidebar */
        html.sidebar-is-collapsed #sidebar,
        .sidebar.collapsed {
            width: 80px !important;
        }

        html.sidebar-is-collapsed #mainContent,
        #mainContent.collapsed-margin {
            margin-left: 80px !important;
        }

        html.sidebar-is-collapsed #header,
        #header.collapsed-left {
            left: 80px !important;
        }

        /* Sembunyikan Teks Secara Smooth */
        .sidebar .menu-text,
        .sidebar .logo-container {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s ease, max-width 0.3s ease;
            max-width: 200px;
            opacity: 1;
        }

        html.sidebar-is-collapsed .sidebar .menu-text,
        html.sidebar-is-collapsed .sidebar .logo-container,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logo-container {
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
            height: 100vh;
        }
    </style>
</head>

<body>

<div class="flex h-screen">

    <!-- Sidebar -->
    <aside
        id="sidebar"
        class="sidebar w-64 bg-slate-900 text-white flex flex-col fixed left-0 top-0 h-screen shadow-xl z-50">

        <!-- Logo Section -->
        <div class="h-20 px-4 flex items-center justify-between border-b border-slate-800">
            <div class="logo-container flex flex-col">
                <h1 class="text-xl font-bold text-white tracking-wide">
                    InJourney Airport
                </h1>
                <p class="text-slate-400 text-xs">
                    FIDS Monitoring
                </p>
            </div>

            <button
                id="toggleSidebar"
                type="button"
                tabindex="-1"
                onclick="return false;"
                class="p-2 rounded-lg hover:bg-slate-800 transition text-slate-300 hover:text-white shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
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
        <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-x-hidden">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="menu-item flex items-center gap-3.5 rounded-xl px-3.5 py-3 transition-colors
                {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z"/>
                </svg>
                <span class="menu-text text-sm font-medium">Dashboard</span>
            </a>

            <!-- Devices -->
            <a href="{{ route('devices') }}"
                class="menu-item flex items-center gap-3.5 rounded-xl px-3.5 py-3 transition-colors
                {{ request()->routeIs('devices*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M4 5h16v10H4V5Z"/>
                </svg>
                <span class="menu-text text-sm font-medium">Devices</span>
            </a>

            <!-- Monitoring -->
            <a href="{{ route('monitoring') }}"
                class="menu-item flex items-center gap-3.5 rounded-xl px-3.5 py-3 transition-colors
                {{ request()->routeIs('monitoring') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h3l2-5 4 10 2-5h3"/>
                </svg>
                <span class="menu-text text-sm font-medium">Monitoring</span>
            </a>

            <!-- History -->
            <a href="{{ route('history') }}"
                class="menu-item flex items-center gap-3.5 rounded-xl px-3.5 py-3 transition-colors
                {{ request()->routeIs('history') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M21 12A9 9 0 1112 3"/>
                </svg>
                <span class="menu-text text-sm font-medium">History</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('notifications') }}"
                class="menu-item flex items-center gap-3.5 rounded-xl px-3.5 py-3 transition-colors
                {{ request()->routeIs('notifications') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>
                </svg>
                <span class="menu-text text-sm font-medium">Notifications</span>
            </a>

        </nav>

        
        <!-- Logout -->
        <div class="p-3 border-t border-slate-800">
            <button type="button" class="menu-item w-full flex items-center gap-3.5 rounded-xl px-3.5 py-3 text-slate-300 hover:bg-slate-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6"/>
                </svg>
                <span class="menu-text text-sm font-medium">Logout</span>
            </button>
        </div>

    </aside>

    <!-- Main Content Area -->
    <div id="mainContent" class="flex-1 ml-64 transition-all duration-300 ease-in-out flex flex-col">

        <!-- Header -->
        <header id="header" class="bg-white h-20 shadow-sm flex items-center justify-between px-8 fixed top-0 left-64 right-0 z-40 transition-all duration-300 ease-in-out">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    @if(request()->routeIs('dashboard'))
                        Dashboard
                    @elseif(request()->routeIs('devices*'))
                        Devices
                    @elseif(request()->routeIs('monitoring'))
                        Monitoring
                    @elseif(request()->routeIs('history'))
                        History
                    @elseif(request()->routeIs('notifications'))
                        Notifications
                    @else
                        Airport Flight Information Display Monitoring
                    @endif
                </h2>
                <p class="text-slate-500 text-xs mt-0.5">
                    Airport Flight Information Display Monitoring
                </p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Icon Notifikasi tunggal dengan link ke halaman notifikasi -->
                <a href="{{ route('notifications') }}"
                    class="relative w-10 h-10 rounded-xl hover:bg-slate-100 transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-slate-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>
                    </svg>

                    @if($unreadNotifications)
                        <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1 bg-red-600 rounded-full text-white text-[11px] flex items-center justify-center font-semibold">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </a>

                <!-- Profile Avatar -->
                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                    A
                </div> 
            </div>
        </header>

        <!-- Content Body -->
        <div class="content mt-20 px-8 py-8">
            @yield('content')
        </div>

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
            sidebar.classList.add('collapsed');
            main.classList.add('collapsed-margin');
            header.classList.add('collapsed-left');
        }

        // Event Toggle Click Murni
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            // Lepas class penahan anti-flicker di <html> jika ada
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

        // Mencegah Tombol Enter di manapun memicu Toggle Sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && document.activeElement === toggle) {
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>
            }
        });

        // Mencegah Tombol Enter di manapun memicu Toggle Sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && document.activeElement === toggle) {
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>