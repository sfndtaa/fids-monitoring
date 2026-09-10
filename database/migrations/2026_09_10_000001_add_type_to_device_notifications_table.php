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
        Schema::table('device_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('device_notifications', 'type')) {
                $table->string('type', 50)->nullable()->after('device_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('device_notifications', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
