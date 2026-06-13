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
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->boolean('lamp_status')->default(false);
            $table->boolean('humidifier_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropColumn(['lamp_status', 'humidifier_status']);
        });
    }
};
