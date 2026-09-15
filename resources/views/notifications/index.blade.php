@extends('layouts.app')

@section('content')

<div class="space-y-4 text-xs">

    <div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">
            Alerts & Notifications
        </h1>
        <p class="text-slate-500 text-xs mt-0.5">
            System generated alerts for device status transitions and disconnections.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200 font-semibold uppercase text-[11px]">
                    <tr>
                        <th class="text-left px-4 py-3">Device Name</th>
                        <th class="text-left px-4 py-3">Message</th>
                        <th class="text-center px-4 py-3">Status</th>
                        <th class="text-center px-4 py-3">Time</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($notifications as $notification)
                    <tr onclick="if(typeof window.playAlertBuzzer === 'function') window.playAlertBuzzer();" class="hover:bg-slate-50 transition cursor-pointer">
                        <td class="px-4 py-3 font-bold text-slate-800">
                            {{ optional($notification->device)->device_name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-slate-700">
                            {{ $notification->message }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($notification->type == 'online')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Online
                                </span>
                            @elseif($notification->type == 'warning')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Warning
                                </span>
                            @elseif($notification->type == 'maintenance')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                                    Maintenance
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-100 text-rose-800 border border-rose-200">
                                    Offline
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center text-slate-500 font-mono">
                            {{ $notification->created_at->format('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-slate-400">
                            No notifications recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($notifications->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if($notifications->count() > 0)
            if (typeof window.playAlertBuzzer === 'function') {
                window.playAlertBuzzer();
            }
        @endif
    });
</script>
@endpush

@endsection