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
            CREATE OR REPLACE FUNCTION is_exam_tree_complete(
                p_exam_id UUID,
                exam_area area_enum
            )
            RETURNS BOOLEAN
            LANGUAGE sql
            AS $$
            WITH RECURSIVE tree AS (
                SELECT id, parent_id, n_questions, exam_id
                FROM exam_requirements
                WHERE exam_id = p_exam_id
                AND area = exam_area
            ),
            children_sum AS (
                SELECT parent_id, SUM(n_questions) AS sum_children
                FROM tree
                WHERE parent_id IS NOT NULL
                GROUP BY parent_id
            )
            SELECT
                COALESCE(
                    BOOL_AND(
                        cs.sum_children IS NULL
                        OR cs.sum_children = t.n_questions
                    ),
                    FALSE
                ) AS is_complete
            FROM tree t
            LEFT JOIN children_sum cs ON t.id = cs.parent_id;
            $$;
            SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS is_exam_tree_complete(UUID, area_enum);');
    }
};
