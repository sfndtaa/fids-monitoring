<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\DeviceNotification;
use Carbon\Carbon;

class PingService
{
    /**
     * Ping single IP address from backend server.
     *
     * @param string $ip
     * @param int $timeoutMs Timeout in milliseconds (default: 800ms)
     * @return array
     */
    public function pingAddress(string $ip, int $timeoutMs = 800): array
    {
        $ip = trim($ip);
        if (empty($ip)) {
            return [
                'success' => false,
                'status' => 'offline',
                'response_time' => null,
                'output' => 'Invalid or empty IP address',
            ];
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $escapedIp = escapeshellarg($ip);

        if ($isWindows) {
            // Windows: -n count, -w timeout in ms
            $command = "ping -n 1 -w {$timeoutMs} {$escapedIp}";
        } else {
            // Linux/Unix: -c count, -W timeout in seconds (minimum 1s)
            $timeoutSec = max(1, (int) ceil($timeoutMs / 1000));
            $command = "ping -c 1 -W {$timeoutSec} {$escapedIp}";
        }

        $outputLines = [];
        $resultCode = 0;
        @exec($command, $outputLines, $resultCode);
        $rawOutput = implode("\n", $outputLines);

        $responseTime = null;
        $isSuccess = false;

        if ($resultCode === 0) {
            // Check for reply with time
            // Patterns: "time=12ms", "time<1ms", "time=0.456 ms"
            if (preg_match('/time[=<]([0-9]+(?:\.[0-9]+)?)\s*ms/i', $rawOutput, $matches)) {
                $responseTime = (int) round((float) $matches[1]);
                if ($responseTime === 0 && str_contains($rawOutput, '<1ms')) {
                    $responseTime = 1;
                }
                $isSuccess = true;
            } elseif (!str_contains($rawOutput, 'Request timed out') &&
                      !str_contains($rawOutput, 'Destination host unreachable') &&
                      !str_contains($rawOutput, '100% packet loss') &&
                      !str_contains($rawOutput, '100% loss')) {
                $isSuccess = true;
                $responseTime = 1;
            }
        }

        if ($isSuccess) {
            // Status warning if high latency (> 300ms)
            $status = ($responseTime !== null && $responseTime > 300) ? 'warning' : 'online';
        } else {
            $status = 'offline';
            $responseTime = null;
        }

        return [
            'success' => $isSuccess,
            'status' => $status,
            'response_time' => $responseTime,
            'output' => $rawOutput,
        ];
    }

    /**
     * Ping specific Device model and persist results to database.
     *
     * @param Device $device
     * @param int $timeoutMs
     * @return array
     */
    public function pingDevice(Device $device, int $timeoutMs = 800): array
    {
        $oldStatus = $device->status ?? 'offline';
        $result = $this->pingAddress($device->ip_address, $timeoutMs);

        $newStatus = $result['status'];
        $responseTime = $result['response_time'];
        $now = Carbon::now();

        // Update Device record
        $device->status = $newStatus;
        $device->response_time = $responseTime;
        $device->last_ping = $now;
        $device->save();

        // Record history log
        DeviceLog::create([
            'device_id' => $device->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'response_time' => $responseTime,
            'checked_at' => $now,
        ]);

        // Trigger notification if device went offline
        if ($oldStatus === 'online' && $newStatus === 'offline') {
            DeviceNotification::create([
                'device_id' => $device->id,
                'message' => "Device '{$device->device_name}' ({$device->ip_address}) pada lokasi '{$device->location}' terputus / offline.",
                'is_read' => false,
            ]);
        }

        return [
            'id' => $device->id,
            'device_name' => $device->device_name,
            'location' => $device->location,
            'ip_address' => $device->ip_address,
            'status' => $newStatus,
            'old_status' => $oldStatus,
            'response_time' => $responseTime,
            'last_ping' => $now->format('d M Y H:i:s'),
            'last_ping_human' => $now->diffForHumans(),
            'success' => $result['success'],
        ];
    }

    /**
     * Ping multiple devices by array of IDs.
     *
     * @param array $deviceIds
     * @param int $timeoutMs
     * @return array
     */
    public function pingBatch(array $deviceIds, int $timeoutMs = 800): array
    {
        $devices = Device::whereIn('id', $deviceIds)->get();
        $results = [];

        foreach ($devices as $device) {
            $results[] = $this->pingDevice($device, $timeoutMs);
        }

        return $results;
    }
}
