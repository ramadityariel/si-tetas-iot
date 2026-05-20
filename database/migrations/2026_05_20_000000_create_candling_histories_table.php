<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candling_histories', function (Blueprint $table) {
            $table->id();
            $table->string('snapshot_path')->nullable();
            $table->string('prediction_result'); // Fertil / Infertil
            $table->integer('confidence_score');
            $table->string('admin_name');
            $table->string('status')->default('Selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candling_histories');
    }
};
