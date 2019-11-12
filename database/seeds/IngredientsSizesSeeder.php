<?php

use Illuminate\Database\Seeder;
use App\IngredientsSizes;
use App\FoodSizes;
use League\Csv\Reader;
use App\FoodIngredients;

class IngredientsSizesSeeder extends Seeder
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
     		if(!empty($csvLine[0])){
                $foodSize = FoodSizes::updateOrCreate(['description' => $csvLine[0] ]);
     
	            $ingredientId = FoodIngredients::where('name', "=", $csvLine[1])->first()->id;

	           	dump($foodSize->id);
	         	dump($ingredientId);

	            IngredientsSizes::updateOrCreate([
	            	'food_size_id' =>  $foodSize->id,
	            	'ingredient_id' => $ingredientId,
	            	'mass_of_ingredient_in_grams' => (int)$csvLine[2]
	            ]);
     		}



        };
    }
    
}
