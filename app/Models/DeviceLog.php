<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceLog extends Model
{
    protected $fillable = [
        'device_id',
        'old_status',
        'new_status',
        'response_time',
        'checked_at'
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function getStatusAttribute(): ?string
    {
        return $this->new_status;
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}