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
    DB::statement("
        DO $$
        BEGIN
            IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'area_enum') THEN
                CREATE TYPE area_enum AS ENUM ('BIOMEDICAS', 'SOCIALES', 'INGENIERIAS', 'TODAS');
            END IF;
        END$$;
    ");

    DB::statement("
        DO $$
        BEGIN
            IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'difficulty_enum') THEN
                CREATE TYPE difficulty_enum AS ENUM ('FACIL', 'MEDIO', 'DIFICIL');
            END IF;
        END$$;
    ");
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