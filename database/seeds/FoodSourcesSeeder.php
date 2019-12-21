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
        $reader = Reader::createFromPath(storage_path() . '/Food_sources.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

            dump($csvLine);

            FoodSources::updateOrCreate([
                'unique_id' => $csvLine[0],
            ],[
                'food' => $csvLine[1],
                'include' => ($csvLine[2] === 'yes'),
                'tags' => $csvLine[3],
                'kgCO2e_per_kg_food' => (float) $csvLine[4],
                'origin_location' => $csvLine[5],
                'source_title' => $csvLine[6],
                'authors' => $csvLine[7],
                'publisher' => $csvLine[8],
                'link' => $csvLine[9]
            ]);

        };

    }
}
