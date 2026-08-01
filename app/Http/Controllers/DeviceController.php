<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::query();

        // Search
        if ($request->filled('search')) {
            $devices->where('device_name', 'like', '%' . $request->search . '%');
        }

        // Filter Location
        if ($request->filled('location')) {
            $devices->where('location', $request->location);
        }

        $devices = $devices
            ->orderBy('device_name')
            ->paginate(15);

        $locations = Device::select('location')
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('devices.index', compact('devices', 'locations'));
    }
}