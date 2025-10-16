<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matrices', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->char('year', 4);
            $table->tinyInteger('n_alternatives')->unsigned();
            $table->unsignedTinyInteger('modality_id')->index();
            $table->timestamps();

            $table->foreign('modality_id')
                ->references('id')
                ->on('modalities')
                ->onDelete('cascade');
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
