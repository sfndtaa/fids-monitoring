<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of devices.
     */
    public function index(Request $request)
    {
        $query = Device::query();

        // Search Device Name, IP Address, or Location
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter Location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter Status
        if ($request->filled('status') && in_array($request->status, ['online', 'offline', 'warning', 'maintenance'])) {
            $query->where('status', $request->status);
        }

        $devices = $query
            ->orderBy('device_name')
            ->paginate(15)
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
}