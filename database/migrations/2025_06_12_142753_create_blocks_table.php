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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('level_id')->index();
            $table->string('code', 20)->unique();
            $table->tinyText('name');
            $table->unsignedInteger('parent_block_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('level_id')
                ->references('id')
                ->on('levels')
                ->onDelete('cascade');

            $table->foreign('parent_block_id')
                ->references('id')
                ->on('blocks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
