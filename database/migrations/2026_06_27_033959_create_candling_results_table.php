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
        Schema::create('candling_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('candling_sessions')->cascadeOnDelete();
            $table->integer('egg_position');
            $table->enum('status_deteksi_ai', ['Fertil Hidup', 'Fertil Mati', 'Infertil'])->nullable();
            $table->float('confidence_score')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_manual_override')->default(false);
            $table->enum('status_manual', ['Fertil Hidup', 'Fertil Mati', 'Infertil'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candling_results');
    }
};
