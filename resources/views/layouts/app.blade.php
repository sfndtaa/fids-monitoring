<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airport FIDS Monitoring</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family:'Inter',sans-serif;
            background:#F5F7FB;
        }
    </style>
</head>
<body>

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white">

        <div class="text-center py-8">

            <h1 class="text-xl font-bold">
                Airport FIDS
            </h1>

            <p class="text-slate-400 text-sm">
                Monitoring System
            </p>

        </div>

        <nav class="px-5 space-y-2">

            <a href="#" class="block rounded-lg px-4 py-3 bg-blue-600">
                Dashboard
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 hover:bg-slate-800">
                Devices
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 hover:bg-slate-800">
                Monitoring
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 hover:bg-slate-800">
                History
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 hover:bg-slate-800">
                Notification
            </a>

        </nav>

    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">

        @yield('content')

    </main>

</div>

</body>
</html>