<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('device_name')->get();

        return view('monitoring.index', compact('devices'));
    }
}