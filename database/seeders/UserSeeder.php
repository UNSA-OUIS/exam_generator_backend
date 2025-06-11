<?php

namespace Database\Seeders;

use App\Helpers\MasterKeyHelper;
use App\Models\MasterKey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Run seeder: php artisan db:seed --class=UserSeeder
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin 1',
            'email' => 'admin1@unsa.edu.pe',
            'password' => '12345678'
        ]);

        $user2 = User::factory()->create([
            'name' => 'Admin 2',
            'email' => 'admin2@unsa.edu.pe',
            'password' => '87654321'
        ]);
    }
}
