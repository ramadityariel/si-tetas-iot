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
            $table->string('rf_prediction')->nullable();
            $table->integer('if_prediction')->nullable();
            $table->float('if_anomaly_score')->nullable();
            $table->string('rule_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            $table->dropColumn(['rf_prediction', 'if_prediction', 'if_anomaly_score', 'rule_status']);
        });
    }
};
