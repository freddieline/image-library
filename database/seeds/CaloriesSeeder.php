<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;


class CaloriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(storage_path().'/Calories.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

            dump($csvLine);

                App\Calories::updateOrCreate([
                'food' => $csvLine[0]
                ], [
                    'calories_per_g' =>$csvLine[3]
                ]);

        };
    }
    
}
