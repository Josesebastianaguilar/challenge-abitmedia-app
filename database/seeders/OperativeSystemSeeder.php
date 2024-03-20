<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OperativeSystem;

class OperativeSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Windows operative system
        OperativeSystem::create([
            'name' => 'Windows',
            'slug' => 'windows',
        ]);

        // Create Mac operative system
        OperativeSystem::create([
            'name' => 'Mac',
            'slug' => 'mac',
        ]);
    }
}
