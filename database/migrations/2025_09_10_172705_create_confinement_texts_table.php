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
        Schema::create('confinement_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('confinement_id')->constrained('confinements')->onDelete('cascade');
            $table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
            $table->integer('texts_to_do');
            $table->integer('questions_per_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confinement_texts');
    }
};
