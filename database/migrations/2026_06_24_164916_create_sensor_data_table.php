<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel ini digunakan untuk menyimpan data sensor suhu dan kelembaban dari ESP32.
     */
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();                          // Primary key auto-increment
            $table->float('suhu');                 // Suhu dalam derajat Celsius
            $table->float('kelembaban');           // Kelembaban relatif dalam persen (%)
            $table->timestamps();                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};
