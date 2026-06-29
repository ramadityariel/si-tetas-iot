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
        Schema::create('candling_sessions', function (Blueprint $table) {
            $table->id();
            $table->enum('tray_id', ['Atas', 'Tengah', 'Bawah']);
            $table->enum('status', ['Pending', 'Processing', 'Done'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candling_sessions');
    }
};
