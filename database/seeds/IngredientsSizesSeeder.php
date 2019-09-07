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
        
        $reader = Reader::createFromPath(storage_path().'/Food_products.csv', 'r');

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {

     	dump($csvLine);
     		if(!empty($csvLine[0])){
     			$foodSizeId = FoodSizes::where('description', "=", $csvLine[0])->first()->id;
	            $ingredientId = FoodIngredients::where('name', "=", $csvLine[1])->first()->id;

	           	dump($foodSizeId);
	         	dump($ingredientId);

	            IngredientsSizes::updateOrCreate([
	            	'food_size_id' =>  $foodSizeId,
	            	'ingredient_id' => $ingredientId,
	            	'mass_of_ingredient_in_grams' => (int)$csvLine[2]
	            ]);
     		}



        };
    }
    
}
