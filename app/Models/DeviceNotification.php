<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceNotification extends Model
{
    protected $fillable = [
        'device_id',
        'message',
        'type',
        'is_read',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}