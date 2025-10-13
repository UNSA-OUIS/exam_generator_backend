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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->unsignedTinyInteger('matrix_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('description');
            $table->tinyInteger('total_variations')->default(1);
            $table->text('status');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE exams ALTER COLUMN status TYPE exam_status_enum USING status::exam_status_enum;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
