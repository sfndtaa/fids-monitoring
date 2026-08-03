@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">
                Notifications
            </h1>

            <p class="text-slate-500 mt-1">
                Monitor all notifications from Airport Information Display devices.
            </p>

        </div>

    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="text-left p-4">Device</th>
                    <th class="text-left p-4">Message</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Time</th>

                </tr>

            </thead>

            <tbody>

                @forelse($notifications as $notification)

                    <tr class="border-t hover:bg-slate-50">

                        <td class="p-4 font-medium">
                            {{ optional($notification->device)->device_name ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $notification->message }}
                        </td>

                        <td class="p-4">

                            @if($notification->type == 'online')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                    Online
                                </span>

                            @elseif($notification->type == 'warning')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">
                                    Warning
                                </span>

                            @elseif($notification->type == 'maintenance')

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                                    Maintenance
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                    Offline
                                </span>

                            @endif

                        </td>

                        <td class="p-4 text-slate-500">
                            {{ $notification->created_at->format('d M Y H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center py-12 text-slate-500">

                            <div class="flex flex-col items-center gap-3">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-14 h-14 text-slate-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="1.5"
                                          d="M15 17h5l-1.4-1.4A2 2 0 0118 14.17V11a6 6 0 10-12 0v3.17c0 .53-.21 1.04-.59 1.43L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>

                                </svg>

                                <div>

                                    <h2 class="font-semibold text-slate-700">
                                        No Notifications
                                    </h2>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Notifications will appear when device status changes.
                                    </p>

                                </div>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div>

        {{ $notifications->links() }}

    </div>

</div>

@endsection