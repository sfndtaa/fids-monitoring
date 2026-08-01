<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InJourney Airport Information Display Monitoring</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Inter', sans-serif;
            background: #F1F5F9;
        }
    </style>
</head>

<body>

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-xl">

        <!-- Logo -->
        <div class="px-8 py-8 border-b border-slate-800">

            <h1 class="text-2xl font-bold tracking-wide">
                InJourney
            </h1>

            <p class="text-slate-400 text-sm mt-1">
                Airport Information Display Monitoring
            </p>

        </div>
<!-- Menu -->
<nav class="flex-1 p-6 space-y-2">

    <a href="{{ route('dashboard') }}"
        class="block rounded-xl px-4 py-3 transition
        {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">
        Dashboard
    </a>

    <a href="{{ route('devices') }}"
        class="block rounded-xl px-4 py-3 transition
        {{ request()->routeIs('devices') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800' }}">
        Devices
    </a>

    <a href="#"
        class="block rounded-xl px-4 py-3 hover:bg-slate-800 transition">
        Monitoring
    </a>

    <a href="#"
        class="block rounded-xl px-4 py-3 hover:bg-slate-800 transition">
        History
    </a>

    <a href="#"
        class="block rounded-xl px-4 py-3 hover:bg-slate-800 transition">
        Notification
    </a>

</nav>

        <!-- Footer -->
        <div class="p-6 border-t border-slate-800">

            <button
                class="w-full rounded-xl border border-slate-700 py-3 text-slate-300 hover:bg-slate-800 transition">
                Logout
            </button>

        </div>

    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white shadow-sm px-8 py-5 flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold text-slate-800">
                    Dashboard
                </h2>

                <p class="text-slate-500 text-sm">
                    InJourney Airport Information Display Monitoring
                </p>

            </div>

            <div class="flex items-center gap-4">

                <button
                    class="w-10 h-10 rounded-full hover:bg-slate-100 transition flex items-center justify-center text-slate-600">
                    🔔
                </button>

                <div
                    class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                    A
                </div>

            </div>

        </header>

        <!-- Content -->
        <main class="flex-1 p-8">

            @yield('content')

        </main>

    </div>

</div>

</body>
</html>