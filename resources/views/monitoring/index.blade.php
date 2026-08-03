@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Airport Flight Information Display Monitoring
            </h1>

            <p class="text-slate-500 mt-2">
                Monitor all Airport Information Display devices in real time.
            </p>

        </div>

        <div class="bg-white rounded-xl shadow-sm px-5 py-3">

            <span class="text-slate-500">
                Total Devices
            </span>

            <h2 class="text-2xl font-bold text-slate-800">
                {{ $devices->count() }}
            </h2>

        </div>

    </div>


    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($devices as $device)

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg transition">

            <div class="p-6">

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-lg font-bold text-slate-800">

                            {{ $device->device_name }}

                        </h2>

                        <p class="text-sm text-slate-500 mt-1">

                            {{ $device->location ?? '-' }}

                        </p>

                    </div>


                    @if($device->status == 'online')

                        <span class="w-4 h-4 rounded-full bg-green-500"></span>

                    @elseif($device->status == 'warning')

                        <span class="w-4 h-4 rounded-full bg-yellow-500"></span>

                    @elseif($device->status == 'maintenance')

                        <span class="w-4 h-4 rounded-full bg-blue-500"></span>

                    @else

                        <span class="w-4 h-4 rounded-full bg-red-500"></span>

                    @endif

                </div>

                <div class="mt-6 border-t pt-5 space-y-4">

                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            IP Address

                        </span>

                        <span class="font-medium">

                            {{ $device->ip_address }}

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Status

                        </span>

                        <span class="font-semibold capitalize">

                            {{ $device->status }}

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Response Time

                        </span>

                        <span>

                            {{ $device->response_time ? $device->response_time . ' ms' : '-' }}

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-slate-500">

                            Last Ping

                        </span>

                        <span>

                            @if($device->last_ping)

                                {{ \Carbon\Carbon::parse($device->last_ping)->format('d M Y H:i:s') }}

                            @else

                                Never

                            @endif

                        </span>

                    </div>

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection