<?php

namespace App\Http\Controllers;

use App\Models\DeviceNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DeviceNotification::latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }
}