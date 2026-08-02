<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->string('device_name');

            // Location boleh kosong
            $table->string('location')->nullable();

            $table->string('ip_address')->unique();

            // Subnet & Gateway juga boleh kosong
            $table->string('subnet')->nullable();
            $table->string('gateway')->nullable();

            $table->enum('status', [
                'online',
                'offline',
                'warning',
                'maintenance' 
            ])->default('offline');

            $table->integer('response_time')->nullable();

            $table->timestamp('last_ping')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};