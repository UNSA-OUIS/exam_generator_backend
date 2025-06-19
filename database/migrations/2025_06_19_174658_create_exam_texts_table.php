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
        Schema::create('exam_texts', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('exam_id')->constrained('exams');
            $table->enum('area', ['BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'TODAS']);
            $table->unsignedBigInteger('block_id')->index();
            $table->unsignedTinyInteger('total_texts');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE exam_texts ALTER COLUMN area TYPE area_enum USING area::area_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_texts');
    }
};
