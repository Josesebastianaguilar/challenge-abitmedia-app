<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Formateo de computadoras Service
        Service::create([
            'name' => 'Formateo de computadoras',
            'sku' => 'SEFORPC001',
            'price' => 25.00
        ]);

        // Create Mantenimiento de computadoras Service
        Service::create([
            'name' => 'Mantenimiento',
            'sku' => 'SEMANTAIN2',
            'price' => 30.00
        ]);
        
        // Create Hora de soporte en software Service
        Service::create([
            'name' => 'Hora de soporte en software',
            'sku' => 'SEHSOPSO03',
            'price' => 50.00
        ]);
    }
}
