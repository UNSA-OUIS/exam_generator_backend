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
            CREATE OR REPLACE FUNCTION analyze_confinement_tree(p_confinement_id UUID)
                RETURNS TABLE (
                    id BIGINT,
                    parent_id BIGINT,
                    questions_to_do INT,
                    sum_children INT,
                    condition TEXT
                )
                LANGUAGE sql
                AS $$
                WITH RECURSIVE tree AS (
                    SELECT
                        id,
                        parent_id,
                        questions_to_do,
                        confinement_id
                    FROM confinement_requirements
                    WHERE confinement_id = p_confinement_id
                ),
                children_sum AS (
                    SELECT
                        parent_id,
                        SUM(questions_to_do) AS sum_children
                    FROM tree
                    WHERE parent_id IS NOT NULL
                    GROUP BY parent_id
                )
                SELECT
                    t.id,
                    t.parent_id,
                    t.questions_to_do,
                    COALESCE(cs.sum_children, 0) AS sum_children,
                    CASE
                        WHEN cs.sum_children is NULL THEN 'LEAF'
                        WHEN cs.sum_children = t.questions_to_do THEN 'COMPLETE'
                        WHEN cs.sum_children < t.questions_to_do THEN 'INCOMPLETE'
                        ELSE 'INVALID'
                    END AS condition
                FROM tree t
                LEFT JOIN children_sum cs ON t.id = cs.parent_id
                ORDER BY t.parent_id NULLS FIRST, t.id;
                $$;
            SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS analyze_confinement_tree(UUID);');
    }
};
