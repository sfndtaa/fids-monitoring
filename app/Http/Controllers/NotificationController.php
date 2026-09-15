<?php

namespace App\Http\Controllers;

use App\Models\DeviceNotification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
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

    /**
     * Return unread notification count & latest alert in JSON format.
     */
    public function unread(): JsonResponse
    {
        $unreadCount = DeviceNotification::where('is_read', false)->count();
        $latest = DeviceNotification::latest()->first();

        return response()->json([
            'unread_count' => $unreadCount,
            'latest' => $latest ? [
                'id' => $latest->id,
                'type' => $latest->type,
                'message' => $latest->message,
                'created_at' => $latest->created_at->format('d M Y H:i:s'),
            ] : null,
        ]);
    }
}