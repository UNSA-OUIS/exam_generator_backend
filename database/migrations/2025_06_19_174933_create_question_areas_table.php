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
        Schema::create('question_areas', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->foreignUuid('question_id')->constrained('questions');
            $table->enum('area', ['BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'TODAS']);
        });

        DB::statement("ALTER TABLE question_areas ALTER COLUMN area TYPE area_enum USING area::area_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_areas');
    }
};
