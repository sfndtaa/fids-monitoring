@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>

        <h1 class="text-3xl font-bold text-slate-800">
            History
        </h1>

        <p class="text-slate-500 mt-1">
            Device monitoring history.
        </p>

    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <table class="w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="text-left p-4">Time</th>
                    <th class="text-left p-4">Device</th>
                    <th class="text-left p-4">Location</th>
                    <th class="text-left p-4">Status</th>
                    <th class="text-left p-4">Response Time</th>

                </tr>

            </thead>

            <tbody>

                @forelse($logs as $log)

                <tr class="border-t hover:bg-slate-50">

                    <td class="p-4">

                        {{ optional($log->checked_at)->format('d M Y H:i:s') }}

                    </td>

                    <td class="p-4 font-medium">

                        {{ $log->device->device_name ?? '-' }}

                    </td>

                    <td class="p-4">

                        {{ $log->device->location ?? '-' }}

                    </td>

                    <td class="p-4">

                        @if($log->status == 'online')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                Online
                            </span>

                        @elseif($log->status == 'warning')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                                Warning
                            </span>

                        @elseif($log->status == 'maintenance')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm">
                                Maintenance
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                Offline
                            </span>

                        @endif

                    </td>

                    <td class="p-4">

                        {{ $log->response_time ? $log->response_time.' ms' : '-' }}

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-8 text-slate-500">

                        No history available.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div>

        {{ $logs->links() }}

    </div>

</div>

@endsection