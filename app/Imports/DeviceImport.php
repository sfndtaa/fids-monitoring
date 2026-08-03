<?php

namespace App\Imports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeviceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['device_name']) &&
            empty($row['ip_address'])
        ) {
            return null;
        }

        return Device::updateOrCreate(
            [
                'ip_address' => trim($row['ip_address'] ?? ''),
            ],
            [
                'device_name' => trim($row['device_name'] ?? ''),
                'location' => trim($row['location'] ?? ''),
                'subnet' => trim($row['subnet_baru'] ?? ''),
                'gateway' => trim($row['gateway_baru'] ?? ''),
            ]
        );
    }
}