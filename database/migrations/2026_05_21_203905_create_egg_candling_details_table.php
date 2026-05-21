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
        Schema::create('egg_candling_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candling_id')->constrained('candling_histories')->onDelete('cascade');
            $table->string('egg_id', 5); // 01 to 88
            $table->enum('prediction_result', ['fertil', 'infertil', 'kosong']);
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_candling_details');
    }
};
