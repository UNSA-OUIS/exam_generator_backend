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
        Schema::create('confinement_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('confinement_id')->constrained('confinements')->onDelete('cascade');
            $table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
            $table->integer('questions_to_do');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confinement_blocks');
    }
};
