<?php

namespace App\Imports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DeviceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Device([
            'device_name' => $row['device_name'],
            'location' => $row['location'],
            'ip_address' => $row['ip_address'],
            'subnet' => $row['subnet_baru'],
            'gateway' => $row['gateway_baru'],
        ]);
    }
}