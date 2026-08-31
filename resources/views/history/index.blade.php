@extends('layouts.app')

@section('content')

<div class="space-y-4 text-xs">

    <div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">
            Monitoring Logs History
        </h1>
        <p class="text-slate-500 text-xs mt-0.5">
            Audit logs of device ICMP ping checks and status transitions.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                    <tr>
                        <th class="text-left px-4 py-3">Time</th>
                        <th class="text-left px-4 py-3">Device Name</th>
                        <th class="text-left px-4 py-3">Location</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Response Time</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-600 font-mono">
                            {{ optional($log->checked_at)->format('d M Y H:i:s') ?? $log->created_at->format('d M Y H:i:s') }}
                        </td>

                        <td class="px-4 py-3 font-bold text-slate-800">
                            {{ $log->device->device_name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $log->device->location ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if(($log->new_status ?? $log->status) == 'online')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Online
                                </span>
                            @elseif(($log->new_status ?? $log->status) == 'warning')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Warning
                                </span>
                            @elseif(($log->new_status ?? $log->status) == 'maintenance')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                    Maintenance
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-100 text-rose-800 border border-rose-200">
                                    Offline
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center font-mono font-bold text-slate-700">
                            {{ $log->response_time ? $log->response_time.' ms' : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">
                            No history logs recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $logs->links() }}
        </div>
        @endif

    </div>

</div>

@endsection