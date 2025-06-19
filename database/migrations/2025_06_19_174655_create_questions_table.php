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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('statement');
            $table->enum('difficulty', ['FACIL', 'MEDIO', 'DIFICIL']);
            $table->enum('status', ['USED', 'AVAILABLE']);
            $table->unsignedInteger('block_id');
            $table->foreignUuid('text_id')->constrained('texts');
            $table->text('resolution_path');
            $table->date('resolution_date');
            $table->unsignedTinyInteger('answer');
            $table->foreignUuid('exam_id')->constrained('exams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
