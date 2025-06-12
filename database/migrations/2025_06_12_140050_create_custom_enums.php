<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE TYPE area_enum AS ENUM ('BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'TODAS');");
        DB::statement("CREATE TYPE difficulty_enum AS ENUM ('FACIL', 'MEDIO', 'DIFICIL');");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS area_enum;");
        DB::statement("DROP TYPE IF EXISTS difficulty_enum;");
    }
};
