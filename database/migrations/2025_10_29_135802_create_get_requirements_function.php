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
        DB::statement(<<<SQL
        CREATE OR REPLACE FUNCTION get_requirements(
            model_name TEXT,
            model_uuid UUID,
            model_area area_enum DEFAULT NULL
        )
        RETURNS TABLE (
            id BIGINT,
            block_id BIGINT,
            difficulty difficulty_enum,
            n_questions smallint
        )
        LANGUAGE plpgsql
        AS $$
        BEGIN
            CASE model_name

                WHEN 'confinement' THEN
                    RETURN QUERY
                    SELECT cr.id,
                        cr.block_id,
                        cr.difficulty,
                        cr.n_questions
                    FROM confinement_requirements cr
                    WHERE cr.confinement_id = model_uuid
                    AND NOT EXISTS (
                        SELECT 1 FROM confinement_requirements c2
                        WHERE c2.parent_id = cr.id
                    );

                WHEN 'matrix' THEN
                    RETURN QUERY
                    SELECT mr.id,
                        mr.block_id,
                        NULL::difficulty_enum AS difficulty,
                        mr.n_questions
                    FROM matrix_requirements mr
                    WHERE mr.matrix_id = model_uuid
                    AND mr.area = model_area
                    AND NOT EXISTS (
                        SELECT 1 FROM matrix_requirements m2
                        WHERE m2.parent_id = mr.id
                    );

                WHEN 'exam' THEN
                    RETURN QUERY
                    SELECT er.id,
                        er.block_id,
                        er.difficulty,
                        er.n_questions
                    FROM exam_requirements er
                    WHERE er.exam_id = model_uuid
                    AND er.area = model_area
                    AND NOT EXISTS (
                        SELECT 1 FROM exam_requirements e2
                        WHERE e2.parent_id = er.id
                    );

                ELSE
                    RAISE EXCEPTION 'Unknown model_name: % (expected confinement, matrix, or exam)', model_name;

            END CASE;
        END;
        $$;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS get_requirements(TEXT, UUID, area_enum);');
    }
};
