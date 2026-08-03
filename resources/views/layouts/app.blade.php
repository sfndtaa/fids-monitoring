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

    <style>

        *{
            font-family:'Inter',sans-serif;
        }

        body{
            background:#F1F5F9;
            overflow:hidden;
        }

        .sidebar{
            transition:.25s;
        }

        .sidebar.collapsed{
            width:90px;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .logo-desc,
        .sidebar.collapsed .logout-text{
            display:none;
        }

        .sidebar.collapsed .menu-item{
            justify-content:center;
        }

        .content{
            overflow-y:auto;
            height:100vh;
        }

    </style>

</head>

<body>

<div class="flex h-screen">

    <!-- Sidebar -->

    <aside
        id="sidebar"
        class="sidebar w-72 bg-slate-900 text-white flex flex-col fixed left-0 top-0 h-screen shadow-xl">

        <!-- Logo -->

        <div class="h-24 px-5 flex items-center justify-between border-b border-slate-800">

            <div>

                <h1 class="logo-text text-2xl font-bold">

                    InJourney Airport

                </h1>

                <p class="logo-desc text-slate-400 text-sm">

                    Flight Information Display Monitoring

                </p>

            </div>

            <button
                id="toggleSidebar"
                class="p-2 rounded-lg hover:bg-slate-800 transition">

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

        <!-- Menu -->

        <nav class="flex-1 px-4 py-6 space-y-2">

            <!-- Dashboard -->

            <a
                href="{{ route('dashboard') }}"
                class="menu-item flex items-center gap-4 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z"/>

                </svg>

                <span class="menu-text">

                    Dashboard

                </span>

            </a>

            <!-- Devices -->

            <a
                href="{{ route('devices') }}"
                class="menu-item flex items-center gap-4 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('devices*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M4 5h16v10H4V5Z"/>

                </svg>

                <span class="menu-text">

                    Devices

                </span>

            </a>

            <!-- Monitoring -->

            <a
                href="{{ route('monitoring') }}"
                class="menu-item flex items-center gap-4 rounded-xl px-4 py-3 transition
                {{ request()->routeIs('monitoring') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 12h3l2-5 4 10 2-5h3"/>

                </svg>

                <span class="menu-text">

                    Monitoring

                </span>

            </a>

            <!-- History -->

            <a
                href="#"
                class="menu-item flex items-center gap-4 rounded-xl px-4 py-3 text-slate-300 hover:bg-slate-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3M21 12A9 9 0 1112 3"/>

                </svg>

                <span class="menu-text">

                    History

                </span>

            </a>

            <!-- Notification -->

            <a
                href="#"
                class="menu-item flex items-center gap-4 rounded-xl px-4 py-3 text-slate-300 hover:bg-slate-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>

                </svg>

                <span class="menu-text">

                    Notification

                </span>

            </a>

        </nav>

        <!-- Logout -->

        <div class="p-4 border-t border-slate-800">

            <button
                class="menu-item w-full flex items-center gap-4 rounded-xl px-4 py-3 hover:bg-slate-800 transition">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H9m4 8H7a2 2 0 01-2-2V6a2 2 0 012-2h6"/>

                </svg>

                <span class="logout-text">

                    Logout

                </span>

            </button>

        </div>

    </aside>

    <!-- Main -->

    <div id="mainContent" class="flex-1 ml-72 transition-all duration-300 flex flex-col">

        <!-- Header -->
         <header class="bg-white h-24 shadow-sm flex items-center justify-between px-8 fixed top-0 left-72 right-0 z-40 transition-all duration-300"
    id="header">

    <div>

        <h2 class="text-3xl font-bold text-slate-800">

            @if(request()->routeIs('dashboard'))

                Dashboard

            @elseif(request()->routeIs('devices*'))

                Devices

            @elseif(request()->routeIs('monitoring'))

                Monitoring

            @else

                Airport Flight Information Display Monitoring

            @endif

        </h2>

        <p class="text-slate-500 mt-1">

            Airport Flight Information Display Monitoring

        </p>

    </div>

    <div class="flex items-center gap-4">

        <button
            class="w-11 h-11 rounded-xl hover:bg-slate-100 transition flex items-center justify-center">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-slate-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0h6Z"/>

            </svg>

        </button>

        <div
            class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">

            A

        </div>

    </div>

</header>

<!-- Content -->

<div class="content mt-24 px-8 py-8">

    @yield('content')

</div>

</div>

</div>

<script>

const sidebar = document.getElementById('sidebar');

const toggle = document.getElementById('toggleSidebar');

const main = document.getElementById('mainContent');

const header = document.getElementById('header');

toggle.addEventListener('click',()=>{

    sidebar.classList.toggle('collapsed');

    if(sidebar.classList.contains('collapsed')){

        main.classList.remove('ml-72');
        main.classList.add('ml-[90px]');

        header.classList.remove('left-72');
        header.classList.add('left-[90px]');

    }else{

        main.classList.remove('ml-[90px]');
        main.classList.add('ml-72');

        header.classList.remove('left-[90px]');
        header.classList.add('left-72');

    }

});

</script>

</body>
</html>