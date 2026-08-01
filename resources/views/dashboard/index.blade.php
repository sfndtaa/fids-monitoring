@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- Welcome -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            Dashboard
        </h1>

        <p class="text-slate-500 mt-1">
            Monitor all Airport Flight Information Display System devices in real time.
        </p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <!-- Total -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition">

            <p class="text-slate-500 text-sm">
                Total Devices
            </p>

            <h2 class="text-4xl font-bold text-slate-800 mt-3">
                {{ $total }}
            </h2>

        </div>

        <!-- Online -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition">

            <p class="text-slate-500 text-sm">
                Online
            </p>

            <h2 class="text-4xl font-bold text-green-600 mt-3">
                {{ $online }}
            </h2>

        </div>

        <!-- Offline -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition">

            <p class="text-slate-500 text-sm">
                Offline
            </p>

            <h2 class="text-4xl font-bold text-red-600 mt-3">
                {{ $offline }}
            </h2>

        </div>

        <!-- Warning -->
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-lg transition">

            <p class="text-slate-500 text-sm">
                Warning
            </p>

            <h2 class="text-4xl font-bold text-yellow-500 mt-3">
                {{ $warning }}
            </h2>

        </div>

    </div>

    <!-- Bottom -->
    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Device Status -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-6">
                Device Status Overview
            </h2>

            <div class="space-y-4">

                <div class="flex justify-between border-b pb-4">
                    <div>
                        <h3 class="font-semibold">AOCC_DEP</h3>
                        <p class="text-sm text-slate-500">AOCC</p>
                    </div>

                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">
                        Offline
                    </span>
                </div>

                <div class="flex justify-between border-b pb-4">
                    <div>
                        <h3 class="font-semibold">AOCC_ARR</h3>
                        <p class="text-sm text-slate-500">AOCC</p>
                    </div>

                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-600 text-sm">
                        Offline
                    </span>
                </div>

                <div class="flex justify-between">
                    <div>
                        <h3 class="font-semibold">Gate A1</h3>
                        <p class="text-sm text-slate-500">Gate</p>
                    </div>

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-sm">
                        Online
                    </span>
                </div>

            </div>

        </div>

        <!-- Quick Information -->
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-lg font-semibold text-slate-800 mb-6">
                System Information
            </h2>

            <div class="space-y-4">

                <div class="flex justify-between">
                    <span class="text-slate-500">Monitoring</span>
                    <span class="font-semibold text-green-600">
                        Active
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Database</span>
                    <span class="font-semibold">
                        Connected
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-500">Devices</span>
                    <span class="font-semibold">
                        {{ $total }}
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection