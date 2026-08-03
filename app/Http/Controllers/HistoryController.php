<?php

namespace App\Http\Controllers;

use App\Models\DeviceLog;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $logs = DeviceLog::with('device')
            ->latest()
            ->paginate(20);

        return view('history.index', compact('logs'));
    }
}