<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HuntingType;

class HuntingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Idempotent: safe to run multiple times
        foreach (['Pīļu medības', 'Dzinēju medības'] as $name) {
            HuntingType::updateOrCreate(
                ['name' => $name],   // unique by name
                []                   // slug is handled in the model's booted()
            );
        }
    }
}
