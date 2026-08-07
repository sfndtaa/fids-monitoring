<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_notifications', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('device_notifications', function (Blueprint $table) {
            $table->dropColumn('is_read');
        });
    }
};