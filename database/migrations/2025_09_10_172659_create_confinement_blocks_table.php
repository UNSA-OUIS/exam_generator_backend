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
        Schema::create('confinement_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('confinement_id')->constrained('confinements')->onDelete('cascade');
            $table->foreignId('block_id')->nullable()->constrained('blocks')->onDelete('cascade');
            $table->text('difficulty')->nullable();
            $table->unsignedTinyInteger('n_questions')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('confinement_requirements')->onDelete('cascade');
            $table->unique(['confinement_id', 'block_id', 'difficulty'], 'unique_confinement_block_difficulty');
            $table->index('parent_id');
        });

        DB::statement("ALTER TABLE confinement_requirements ALTER COLUMN difficulty TYPE difficulty_enum USING difficulty::difficulty_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confinement_requirements');
    }
};
