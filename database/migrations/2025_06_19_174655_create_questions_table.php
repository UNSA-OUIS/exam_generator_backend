<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->enum('status', ['DISPONIBLE', 'NO DISPONIBLE', 'USADA', 'DESCARTADA']);
            $table->unsignedInteger('block_id');
            $table->foreignUuid('text_id')->nullable()->constrained('texts');
            $table->unsignedInteger('formulator_id')->constrained('collaborators');
            $table->unsignedInteger('validator_id')->constrained('collaborators');
            $table->unsignedInteger('style_editor_id')->constrained('collaborators');
            $table->unsignedInteger('digitizer_id')->constrained('collaborators');
            $table->text('resolution_path');
            $table->unsignedTinyInteger('answer');
            $table->foreignUuid('exam_id')->nullable()->constrained('exams');
            $table->foreignUuid('confinement_id')->constrained('confinements');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('modified_by');
            $table->timestamps();

            $table->index(['block_id']);

            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
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
