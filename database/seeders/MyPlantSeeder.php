<?php

namespace Database\Seeders;

use App\Models\MyPlant;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MyPlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        /*
         * we'll add 50 records to the db
         * random users, random plants
         * a person could hold multiple of the same plant, so we won't validate against that
         * */
        // call me weird but I think this is cleaner than: for ($x = 0; $x <= 50; $x++)
        foreach(range(1,50) as $i){
            MyPlant::create([
                'user_id' => rand(1,5),
                'plant_id' => rand(1,5),
                // fake a date between today and last month, and format it
                'last_watered' => date_format($faker->dateTimeBetween('-1 month'), 'Y-m-d'),
            ]);
        }
    }
}
