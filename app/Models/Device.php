<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_name',
        'location',
        'ip_address',
        'subnet',
        'gateway',
        'status',
        'response_time',
        'last_ping',
    ];
   
    public function logs()
    {
        return $this->hasMany(DeviceLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(DeviceNotification::class);
    }
}