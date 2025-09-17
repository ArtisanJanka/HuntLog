<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unique check
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin1234*'),
                'is_admin' => true,
            ]
        );
    }
}
