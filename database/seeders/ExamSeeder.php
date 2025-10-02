<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Matrix;
use App\Models\Modality;
use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = Exam::create([
            'matrix_id' => Matrix::first()->id,
            'user_id' => 1,
            'description' => 'Examen de admisión 2025-I',
            'total_variations' => 3,
        ]);
    }
}
