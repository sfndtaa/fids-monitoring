<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceLog;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'total' => Device::count(),
            'online' => Device::where('status', 'online')->count(),
            'offline' => Device::where('status', 'offline')->count(),
            'warning' => Device::where('status', 'warning')->count(),
            'maintenance' => Device::where('status', 'maintenance')->count(),
            'recentDevices' => Device::latest()->take(6)->get(),
            'recentLogs' => DeviceLog::with('device')->latest('checked_at')->take(6)->get(),
        ]);
    }
}