<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Collection;
use App\FoodIngredients;
use App\FoodProducts;
use App\FoodProductsIngredients;

class FoodProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(storage_path().'/Food_products.csv', 'r');

        $ingredients = FoodIngredients::all();

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {


                $foodProduct = App\FoodProducts::updateOrCreate([
                	'name' => $csvLine[0]
                ],[
                    'is_liquid' => $csvLine[1] === "yes",
                    'dietary_requirements' => $csvLine[2],
                    'category' => $csvLine[3],
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
               	$ingredient5 = 0;
             
                // insert ingredient 1
                $ingredient1 = $ingredients->where('name',$csvLine[4])->where('standard_deviation',"!=", null)->first()->id;
 
            	FoodProductsIngredients::updateOrCreate([
                    'food_product_id' => $foodProduct->id,
                    'ingredient_id' =>  $ingredient1,
                    'ratio' => (integer)$csvLine[5]
                ]);

                // insert ingredient 2
                if(!empty($csvLine[6])){
                    dump($csvLine[6]);
                    $ingredient2 = $ingredients->where('name',$csvLine[6])->where('standard_deviation',"!=", null)->first()->id;
                      dump($ingredient2);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient2,
                        'ratio' => (integer)$csvLine[7]
                    ]);
                }

                // insert ingredient 3
                if(!empty($csvLine[8])){
                    dump($csvLine[8]);
                    $ingredient3 = $ingredients->where('name',$csvLine[8])->where('standard_deviation',"!=", null)->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient3,
                        'ratio' => (integer)$csvLine[9]
                    ]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[10])){
                    dump($csvLine[10]);
                    $ingredient4 = $ingredients->where('name',$csvLine[10])->where('standard_deviation',"!=", null)->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' => $ingredient4,
                        'ratio' => (integer)$csvLine[11]
                    ]);
                }

                 // insert ingredient 5
                if(!empty($csvLine[12])){
                     dump($csvLine[12]);
                    $ingredient5 = $ingredients->where('name',$csvLine[12])->where('standard_deviation',"!=", null)->first()->id;
                  	dump($ingredient5);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[13]
                    ]);
                }

        }
    
    }
}