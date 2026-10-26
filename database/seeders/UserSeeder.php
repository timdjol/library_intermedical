<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('SEED_PASSWORD');

        if (! is_string($password) || strlen($password) < 8) {
            throw new \RuntimeException('Задайте SEED_PASSWORD длиной не меньше 8 символов перед запуском сидера.');
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Agent',
            'email' => 'agent@gmail.com',
            'password' => Hash::make($password),
            'role' => 'agent',
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make($password),
            'role' => 'user',
        ]);
    }
}
