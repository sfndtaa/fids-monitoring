<?php

namespace App\Http\Controllers;

use App\Models\DeviceNotification;

class NotificationController extends Controller
{
    public function index()
    {
        DeviceNotification::where('is_read', false)
            ->update([
                'is_read' => true
            ]);

        $notifications = DeviceNotification::with('device')
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }
}