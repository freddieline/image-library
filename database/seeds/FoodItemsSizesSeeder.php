<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Collection;
use App\FoodIngredients;
use App\FoodProducts;
use App\FoodSizes;
use App\FoodItemsSizes;


class FoodItemsSizesSeeder extends Seeder
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
				dump($csvLine[1]);
     
                if(FoodIngredients::where('name', "=", $csvLine[1])->exists()){
                   $ingredientId = FoodIngredients::where('name', "=", $csvLine[1])->first()->id;

                    dump($foodSize->id);
                    dump($ingredientId);

                    FoodItemsSizes::updateOrCreate([
                        'food_size_id' =>  $foodSize->id,
                        'ingredient_id' => $ingredientId,
                        'mass_in_grams' => (int)$csvLine[2],
                        'purchasable' => $csvLine[3] === 'yes'
                    ]);
                }
                else{
                   $foodProductId = FoodProducts::where('name', "=", $csvLine[1])->first()->id;

                    dump($foodSize->id);
                    dump($foodProductId);

                    FoodItemsSizes::updateOrCreate([
                        'food_size_id' =>  $foodSize->id,
                        'food_product_id' => $foodProductId,
                        'mass_in_grams' => (int)$csvLine[2],
                        'purchasable' => $csvLine[3] === 'yes'
                    ]);
                }
	     
     		}



        };
    }
}
