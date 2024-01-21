<?php

namespace Database\Seeders;

use App\Models\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // build some plants
        $plantsArray = [
            0 => [
                'name' => 'European Mountain Ash',
                'latin_name' => 'Sorbus Aucuparia',
                'water_frequency' => 7,
                'sunlight' => 2,
            ],
            1 => [
                'name' => 'Christmas Tree',
                'latin_name' => 'Abies Alba',
                'water_frequency' => 3,
                'sunlight' => 1,
            ],
            2 => [
                'name' => 'Spreading Hedgeparsley',
                'latin_name' => 'Torilis Arvensis',
                'water_frequency' => 14,
                'sunlight' => 2,
            ],
            3 => [
                'name' => 'White Ash',
                'latin_name' => 'Fraxinus Americana',
                'water_frequency' => 10,
                'sunlight' => 3,
            ],
            4 => [
                'name' => 'Clustered Field Sedge',
                'latin_name' => 'Carex Praegracilis',
                'water_frequency' => 2,
                'sunlight' => 4,
            ],
        ];

        // add them to the seed
        foreach($plantsArray as $plant){
            Plant::create([
                'name' => $plant['name'],
                'latin_name' => $plant['latin_name'],
                'water_frequency' => $plant['water_frequency'],
                'sunlight' => $plant['sunlight'],
            ]);
        }
    }
}
