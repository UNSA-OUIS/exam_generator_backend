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
        Schema::create('exam_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('exam_id')->constrained('exams')->onDelete('cascade');
            $table->text('area');
            $table->unsignedInteger('block_id')->nullable();
            $table->text('difficulty')->nullable();
            $table->unsignedTinyInteger('n_questions')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('exam_requirements')->onDelete('cascade');

            $table->unique(['exam_id', 'area', 'block_id', 'difficulty'], 'unique_exam_area_block_difficulty');
            $table->index('parent_id');
        });

        DB::statement("ALTER TABLE exam_requirements ALTER COLUMN area TYPE area_enum USING area::area_enum;");
        DB::statement("ALTER TABLE exam_requirements ALTER COLUMN difficulty TYPE difficulty_enum USING difficulty::difficulty_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_requirements');
    }
};
