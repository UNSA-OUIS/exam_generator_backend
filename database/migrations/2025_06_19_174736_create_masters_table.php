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
        Schema::create('masters', function (Blueprint $table) {
            $table->id();
            $table->enum('area', ['BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'UNICA']);
            $table->foreignUuid('exam_id')->constrained('exams');
            $table->foreignUuid('question_id')->constrained('questions');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE masters ALTER COLUMN area TYPE area_enum USING area::area_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters');
    }
};
