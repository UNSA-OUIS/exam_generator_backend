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
        Schema::create('matrices', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->char('year', 4);
            $table->tinyInteger('n_alternatives')->unsigned();
            $table->unsignedTinyInteger('modality_id');
            $table->timestamps();

            $table->foreign('modality_id')
                ->references('id')
                ->on('modalities')
                ->onDelete('cascade');

            $table->unique(['year', 'modality_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matrices');
    }
};
