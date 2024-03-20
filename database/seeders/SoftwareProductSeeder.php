<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoftwareProduct;

class SoftwareProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Antivirus software product
        SoftwareProduct::create([
            'name' => 'Antivirus',
            'sku' => 'SOPAOSWM01',
        ]);

        // Create Ofimática software product
        SoftwareProduct::create([
            'name' => 'Ofimática',
            'sku' => 'SOPOOSWM02',
        ]);

        // Create Editor de video software product
        SoftwareProduct::create([
            'name' => 'Editor de video',
            'sku' => 'SOPVEOSWM3',
        ]);
    }
}
