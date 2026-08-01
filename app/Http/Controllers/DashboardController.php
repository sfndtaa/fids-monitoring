<?php

namespace App\Http\Controllers;

use App\Models\Device;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [

            'total' => Device::count(),

            'online' => Device::where('status','online')->count(),

            'offline' => Device::where('status','offline')->count(),

            'warning' => Device::where('status','warning')->count(),

            'recentDevices' => Device::latest()->take(5)->get()

        ]);
    }
}