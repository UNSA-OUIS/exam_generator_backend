<?php

namespace Database\Seeders;

use App\Models\Confinement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConfinementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $confinement = Confinement::create([
            'id' => Str::uuid(),
            'name' => 'internamiento 2025 1',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);
    }
}
