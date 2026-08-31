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
     * @param int $timeoutMs Timeout in milliseconds (default: 600ms)
     * @return array
     */
    public function pingAddress(string $ip, int $timeoutMs = 600): array
    {
        $ip = trim($ip);
        if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
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
            // Windows: -n 1 (1 packet), -w timeout in ms
            $command = "ping -n 1 -w {$timeoutMs} {$escapedIp}";
        } else {
            // Linux/Unix: -c 1, -W timeout in seconds (min 1)
            $timeoutSec = max(1, (int) ceil($timeoutMs / 1000));
            $command = "ping -c 1 -W {$timeoutSec} {$escapedIp}";
        }

        $outputLines = [];
        $resultCode = 0;
        @exec($command, $outputLines, $resultCode);
        $rawOutput = implode("\n", $outputLines);

        $responseTime = null;
        $isSuccess = false;

        // Check for common failure keywords in English & Indonesian Windows
        $hasFailedKeyword = preg_match('/(unreachable|tidak dapat dijangkau|timed out|waktu habis|100% loss|100% hilang|100% packet loss|general failure|kegagalan umum|could not find host)/i', $rawOutput);

        // Check for TTL presence (universal indicator of successful ICMP reply)
        $hasTtl = preg_match('/ttl[=:]\s*([0-9]+)/i', $rawOutput);

        // Check for latency / time indicator (supports English "time=Xms", Indonesian "waktu=Xms" / "waktu<1ms", "time<1ms")
        $hasTime = preg_match('/(?:time|waktu|tempo)[=<]([0-9]+(?:\.[0-9]+)?)\s*(?:ms|md)?/i', $rawOutput, $timeMatches);

        if ($resultCode === 0 && !$hasFailedKeyword && ($hasTtl || $hasTime)) {
            $isSuccess = true;
            if ($hasTime && isset($timeMatches[1])) {
                $responseTime = (int) round((float) $timeMatches[1]);
                if ($responseTime === 0 && (str_contains($rawOutput, '<1ms') || str_contains($rawOutput, '<1md') || str_contains($rawOutput, '<1 ms'))) {
                    $responseTime = 1;
                }
            } else {
                $responseTime = 1;
            }
        }

        if ($isSuccess) {
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
    public function pingDevice(Device $device, int $timeoutMs = 600): array
    {
        $oldStatus = $device->status ?? 'offline';
        $result = $this->pingAddress($device->ip_address, $timeoutMs);

        $newStatus = $result['status'];
        $responseTime = $result['response_time'];
        $now = Carbon::now();

        // Update Device record in database
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
    public function pingBatch(array $deviceIds, int $timeoutMs = 600): array
    {
        $devices = Device::whereIn('id', $deviceIds)->get();
        $results = [];

        foreach ($devices as $device) {
            $results[] = $this->pingDevice($device, $timeoutMs);
        }

        return $results;
    }
}
