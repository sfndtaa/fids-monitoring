<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceNotification extends Model
{
    protected $fillable = [
        'device_id',
        'type',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}