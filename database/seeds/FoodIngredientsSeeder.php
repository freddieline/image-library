<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class FoodIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(storage_path().'/Food_ingredients.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

            dump($csvLine);

                App\FoodIngredients::updateOrCreate([
                'name' => $csvLine[0]
                ], [
                    'category' =>$csvLine[1]
                ]);

        };
    }
}
