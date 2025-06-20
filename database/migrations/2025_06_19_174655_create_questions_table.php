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
            $table->foreignUuid('formulator_id')->constrained('participants');
            $table->foreignUuid('validator_id')->constrained('participants');
            $table->foreignUuid('style_editor_id')->constrained('participants');
            $table->foreignUuid('digitador_id')->constrained('participants');
            $table->text('resolution_path');
            $table->date('resolution_date');
            $table->unsignedTinyInteger('answer');
            $table->foreignUuid('exam_id')->constrained('exams');
            $table->unsignedBigInteger('confinement_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('modified_by');
            $table->timestamps();

            $table->index(['block_id']);

            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
                ->onDelete('cascade');

            $table->foreign('confinement_id')
                ->references('id')
                ->on('confinements')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->foreign('modified_by')
                ->references('id')
                ->on('users');
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
