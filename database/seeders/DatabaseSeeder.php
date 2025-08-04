<?php

namespace Database\Seeders;

use App\Models\Matrix;
use App\Models\Process;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProcessSeeder::class,
            LevelSeeder::class,
            MatrixSeeder::class,
            MatrixDetailSeeder::class,
        ]);
    }
}
