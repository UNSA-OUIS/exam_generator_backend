<?php

namespace Database\Seeders;

use App\Models\Matrix;
use App\Models\Modality;
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
            'n_alternatives' => 5,
            'modality_id' => Modality::where('name', 'Ordinario')->first()->id,
        ]);
    }
}
