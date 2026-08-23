<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Services\PingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    
    public function index(Request $request)
    {
        $statusFilter = $request->get('status', 'all');
        $locationFilter = $request->get('location', 'all');
        $search = $request->get('search');
        $viewMode = $request->get('view', 'tree'); // 'tree' or 'table'

        $allDevices = Device::orderBy('device_name')->get();
        $stats = [
            'total' => $allDevices->count(),
            'online' => $allDevices->where('status', 'online')->count(),
            'offline' => $allDevices->where('status', 'offline')->count(),
            'warning' => $allDevices->where('status', 'warning')->count(),
            'maintenance' => $allDevices->where('status', 'maintenance')->count(),
        ];

        $locations = Device::select('location')
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $query = Device::query();

        if ($statusFilter !== 'all' && in_array($statusFilter, ['online', 'offline', 'warning', 'maintenance'])) {
            $query->where('status', $statusFilter);
        }

        if ($locationFilter !== 'all' && !empty($locationFilter)) {
            if ($locationFilter === '_unassigned_') {
                $query->where(function ($q) {
                    $q->whereNull('location')->orWhere('location', '');
                });
            } else {
                $query->where('location', $locationFilter);
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $devices = (clone $query)->orderBy('device_name')->get();

        $tableDevices = (clone $query)
            ->orderBy('device_name')
            ->paginate(20)
            ->withQueryString();

        $groupedDevices = $devices->groupBy(function ($device) {
            $loc = trim($device->location ?? '');
            return empty($loc) ? 'OTHER / UNASSIGNED' : $loc;
        })->sortKeys();

        return view('monitoring.index', compact(
            'devices',
            'tableDevices',
            'groupedDevices',
            'stats',
            'locations',
            'statusFilter',
            'locationFilter',
            'search',
            'viewMode'
        ));
    }

    
    public function ping(Device $device, PingService $pingService): JsonResponse
    {
        $result = $pingService->pingDevice($device);

        return response()->json([
            'success' => true,
            'message' => "Ping to {$device->device_name} ({$device->ip_address}) completed.",
            'data' => $result,
        ]);
    
    }
    
        public function pingBatch(Request $request, PingService $pingService): JsonResponse
    {
        $request->validate([
            'device_ids' => 'required|array',
            'device_ids.*' => 'integer|exists:devices,id',
            'timeout' => 'nullable|integer|min:200|max:3000',
        ]);

        $timeout = $request->input('timeout', 800);
        $deviceIds = $request->input('device_ids', []);

        $results = $pingService->pingBatch($deviceIds, $timeout);


        $allDevices = Device::all();
        $stats = [
            'total' => $allDevices->count(),
            'online' => $allDevices->where('status', 'online')->count(),
            'offline' => $allDevices->where('status', 'offline')->count(),
            'warning' => $allDevices->where('status', 'warning')->count(),
            'maintenance' => $allDevices->where('status', 'maintenance')->count(),
        ];

        return response()->json([
            'success' => true,
            'results' => $results,
            'stats' => $stats,
        ]);
    }

    /**
     * Get JSON data for instant refresh without page reload.
     */
    public function data(Request $request): JsonResponse
    {
        $allDevices = Device::orderBy('device_name')->get();
        $stats = [
            'total' => $allDevices->count(),
            'online' => $allDevices->where('status', 'online')->count(),
            'offline' => $allDevices->where('status', 'offline')->count(),
            'warning' => $allDevices->where('status', 'warning')->count(),
            'maintenance' => $allDevices->where('status', 'maintenance')->count(),
        ];

        $devices = $allDevices->map(function ($d) {
            return [
                'id' => $d->id,
                'device_name' => $d->device_name,
                'location' => $d->location ?? 'OTHER / UNASSIGNED',
                'ip_address' => $d->ip_address,
                'subnet' => $d->subnet,
                'gateway' => $d->gateway,
                'status' => $d->status,
                'response_time' => $d->response_time,
                'last_ping' => $d->last_ping ? $d->last_ping->format('d M Y H:i:s') : null,
                'last_ping_human' => $d->last_ping ? $d->last_ping->diffForHumans() : 'Never',
                'url' => route('devices.show', $d->id),
            ];
        });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'devices' => $devices,
            'timestamp' => now()->format('d M Y H:i:s'),
        ]);
    }
}