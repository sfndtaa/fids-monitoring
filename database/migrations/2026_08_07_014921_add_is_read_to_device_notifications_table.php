<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('device_notifications', 'is_read')) {
            Schema::table('device_notifications', function (Blueprint $table) {
                $table->boolean('is_read')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('device_notifications', 'is_read')) {
            Schema::table('device_notifications', function (Blueprint $table) {
                $table->dropColumn('is_read');
            });
        }
    }
};