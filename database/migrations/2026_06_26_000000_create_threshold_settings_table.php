<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threshold_settings', function (Blueprint $table) {
            $table->id();
            $table->float('temp_min_ideal')->default(37.0);
            $table->float('temp_max_ideal')->default(38.0);
            $table->float('hum_min_ideal')->default(55.0);
            $table->float('hum_max_ideal')->default(60.0);
            $table->float('temp_min_ekstrem')->default(36.0);
            $table->float('temp_max_ekstrem')->default(39.0);
            $table->float('hum_min_ekstrem')->default(50.0);
            $table->float('hum_max_ekstrem')->default(65.0);
            $table->string('updated_by')->default('system');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threshold_settings');
    }
};
