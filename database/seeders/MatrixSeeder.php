<?php

namespace Database\Seeders;

use App\Models\Matrix;
use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matrix = Matrix::create([
            'year' => '2025',
            'total_alternatives' => 5,
            'process_id' => Process::where('name', 'Ordinario')->first()->id,
        ]);
    }
}
