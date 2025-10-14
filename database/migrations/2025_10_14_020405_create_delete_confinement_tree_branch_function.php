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
        DB::statement('CREATE OR REPLACE FUNCTION delete_confinement_tree_branch(p_node_id BIGINT)
            RETURNS INTEGER
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_deleted_count INTEGER;
            BEGIN
                WITH RECURSIVE branch AS (
                    SELECT id
                    FROM confinement_requirements
                    WHERE id = p_node_id
                    UNION ALL
                    SELECT cr.id
                    FROM confinement_requirements cr
                    INNER JOIN branch b ON cr.parent_id = b.id
                )
                DELETE FROM confinement_requirements
                WHERE id IN (SELECT id FROM branch)
                RETURNING id INTO v_deleted_count;

                GET DIAGNOSTICS v_deleted_count = ROW_COUNT;

                RETURN v_deleted_count;
            END;
            $$;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS delete_confinement_tree_branch(BIGINT);');
    }
};
