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
        if ($request->search) {
            $devices->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter lokasi
        if ($request->location) {
            $devices->where('location', $request->location);
        }


        return view('devices.index', [
            'devices' => $devices->paginate(15)
        ]);
    }
}