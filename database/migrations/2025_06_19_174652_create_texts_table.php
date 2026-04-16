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
        Schema::create('texts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('content');
            $table->unsignedBigInteger('block_id');
            $table->text('status');
            $table->smallInteger('n_questions')->default(0);
            $table->timestamps();

            $table->index(['block_id']);

            $table->foreign('block_id')
                ->references('id')
                ->on('blocks')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE texts ALTER COLUMN status TYPE question_status_enum USING status::question_status_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('texts');
    }
};
