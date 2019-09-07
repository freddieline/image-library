<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class FoodSizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $reader = Reader::createFromPath(storage_path().'/Food_sizes.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

            dump($csvLine);

                App\FoodSizes::updateOrCreate([
                	'description' => $csvLine[0]
                ]);

        };
    }
}
