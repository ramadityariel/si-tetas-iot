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
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->float('water_level')->nullable()->after('kelembaban');
        });
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->float('water_level')->nullable()->after('humidity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn('water_level');
        });
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropColumn('water_level');
        });
    }
};
