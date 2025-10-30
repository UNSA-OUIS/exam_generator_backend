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
        Schema::create('exam_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('exam_id')->constrained('exams');
            $table->text('area');
            $table->enum('variation', ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']);
            $table->unsignedTinyInteger('position');
            $table->foreignUuid('question_id')->constrained('questions');
            $table->json('options');
            $table->timestamps();

            $table->unique(['exam_id', 'area', 'variation', 'question_id']);
        });

        DB::statement("ALTER TABLE exam_layouts ALTER COLUMN area TYPE area_enum USING area::area_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_layouts');
    }
};
