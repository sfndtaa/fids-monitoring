<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
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

        $devices = $query
            ->orderBy('device_name')
            ->paginate(15)
            ->withQueryString();

        $locations = Device::select('location')
            ->whereNotNull('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('devices.index', compact(
            'devices',
            'locations'
        ));
    }
}