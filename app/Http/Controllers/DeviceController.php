<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\DeviceNotification;
use App\Services\PingService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of devices.
     */
    public function index(Request $request)
    {
        $query = Device::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('status') && in_array($request->status, ['online', 'offline', 'warning', 'maintenance'])) {
            $query->where('status', $request->status);
        }

        $devices = $query
            ->orderBy('device_name')
            ->paginate(20)
            ->withQueryString();

        $locations = Device::select('location')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('devices.index', compact(
            'devices',
            'locations'
        ));
    }

    /**
     * Display the specified device details.
     */
    public function show(Device $device)
    {
        $logs = $device->logs()
            ->latest('checked_at')
            ->take(20)
            ->get();

        $notifications = $device->notifications()
            ->latest()
            ->take(10)
            ->get();

        return view('devices.show', compact(
            'device',
            'logs',
            'notifications'
        ));
    }

    /**
     * Toggle Maintenance mode for a specific device.
     */
    public function toggleMaintenance(Request $request, Device $device, PingService $pingService)
    {
        $oldStatus = $device->status ?? 'offline';

        if ($oldStatus === 'maintenance') {
            // Exit Maintenance: ping device to restore live network status
            $pingResult = $pingService->pingDevice($device);
            $newStatus = $pingResult['status'];

            DeviceNotification::create([
                'device_id' => $device->id,
                'type' => $newStatus === 'online' ? 'online' : ($newStatus === 'warning' ? 'warning' : 'offline'),
                'message' => "Perangkat '{$device->device_name}' ({$device->ip_address}) pada lokasi '{$device->location}' telah SELESAI MAINTENANCE (Status Live: " . strtoupper($newStatus) . ").",
                'is_read' => false,
            ]);

            $action = 'completed';
            $message = "Perangkat '{$device->device_name}' berhasil dikeluarkan dari mode Maintenance. Status live saat ini: " . strtoupper($newStatus) . " (" . ($pingResult['response_time'] !== null ? $pingResult['response_time'] . ' ms' : 'Unreachable') . ").";
        } else {
            // Enter Maintenance
            $device->status = 'maintenance';
            $device->response_time = null;
            $device->save();

            DeviceLog::create([
                'device_id' => $device->id,
                'old_status' => $oldStatus,
                'new_status' => 'maintenance',
                'response_time' => null,
                'checked_at' => now(),
            ]);

            DeviceNotification::create([
                'device_id' => $device->id,
                'type' => 'maintenance',
                'message' => "Perangkat '{$device->device_name}' ({$device->ip_address}) pada lokasi '{$device->location}' telah ditandai dalam status MAINTENANCE / Pemeliharaan Teknisi.",
                'is_read' => false,
            ]);

            $action = 'entered';
            $message = "Perangkat '{$device->device_name}' berhasil dialihkan ke status MAINTENANCE / Pemeliharaan.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'action' => $action,
                'message' => $message,
                'device_name' => $device->device_name,
                'ip_address' => $device->ip_address,
                'location' => $device->location,
                'status' => $device->status,
                'response_time' => $device->response_time,
            ]);
        }

        return back()
            ->with('success', $message)
            ->with('maintenance_action', $action)
            ->with('maintenance_device', $device->device_name)
            ->with('maintenance_ip', $device->ip_address)
            ->with('maintenance_location', $device->location);
    }
}