<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use App\FoodSources;
use League\Csv\Reader;

class FoodSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(storage_path().'/Food_sources.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

            dump($csvLine);

            FoodSources::updateOrCreate([
                'unique_id' => $csvLine[0],
            ],[
                'food' => $csvLine[1],
                'tags' => $csvLine[2],
                'kgCO2e_per_kg_food' => (float)$csvLine[3],
                'origin_location' => $csvLine[4],
                'source_title' => $csvLine[5],
                'authors' => $csvLine[6],
                'publisher' =>$csvLine[7],
                'link' => $csvLine[8]

            ]);

        };

    }
}
