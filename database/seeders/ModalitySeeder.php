<?php

namespace Database\Seeders;

use App\Models\Modality;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modality = Modality::create([
            'name' => 'Ordinario',
        ]);
    }
}
