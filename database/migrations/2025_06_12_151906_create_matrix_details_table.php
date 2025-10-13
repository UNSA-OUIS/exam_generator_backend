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
        Schema::create('matrix_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('matrix_id')->index();
            $table->text('area');
            $table->unsignedInteger('block_id')->nullable()->index();
            $table->unsignedTinyInteger('n_questions')->default(0);
            $table->unsignedInteger('parent_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->foreign('matrix_id')->references('id')->on('matrices')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('matrix_requirements')->onDelete('cascade');
            $table->unique(['matrix_id', 'area', 'block_id'], 'unique_matrix_area_block');
        });

        // Alter columns to use PostgreSQL ENUMs
        DB::statement("ALTER TABLE matrix_requirements ALTER COLUMN area TYPE area_enum USING area::area_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrix_requirements');
    }
};
