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
        Schema::create('matrix_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('matrix_id')->index();
            $table->enum('area', ['BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'UNICA']);

            $table->unsignedInteger('block_id')->index();
            $table->enum('difficulty', ['FACIL', 'MEDIO', 'DIFICIL']);

            $table->unsignedTinyInteger('questions_required')->default(0);
            $table->unsignedTinyInteger('questions_to_do')->default(0);
            $table->timestamps();
        });

        // Alter columns to use PostgreSQL ENUMs
        DB::statement("ALTER TABLE matrix_details ALTER COLUMN area TYPE area_enum USING area::area_enum;");
        DB::statement("ALTER TABLE matrix_details ALTER COLUMN difficulty TYPE difficulty_enum USING difficulty::difficulty_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_details');
    }
};
