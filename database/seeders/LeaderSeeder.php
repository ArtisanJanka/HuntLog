<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'leader@example.com'], // unique key
            [
                'name' => 'Hunting Leader',
                'password' => Hash::make('password123'), // 🔑 change this later
                'is_leader' => true,
            ]
        );
    }
}
