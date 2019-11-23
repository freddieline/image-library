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
                    'category' => $csvLine[1],
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
               	$ingredient5 = 0;
             
                // insert ingredient 1
                $ingredient1 = $ingredients->where('name',$csvLine[2])->first()->id;
 
            	FoodProductsIngredients::updateOrCreate([
                    'food_product_id' => $foodProduct->id,
                    'ingredient_id' =>  $ingredient1,
                    'ratio' => (integer)$csvLine[3]
                ]);

                // insert ingredient 2
                if(!empty($csvLine[4])){
                    $ingredient2 = $ingredients->where('name',$csvLine[4])->first()->id;
                      dump($ingredient2);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient2,
                        'ratio' => (integer)$csvLine[5]
                    ]);
                }

                // insert ingredient 3
                if(!empty($csvLine[6])){
                                dump($csvLine[6]);
                    $ingredient3 = $ingredients->where('name',$csvLine[6])->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient3,
                        'ratio' => (integer)$csvLine[7]
                    ]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[8])){
                                dump($csvLine[8]);
                    $ingredient4 = $ingredients->where('name',$csvLine[8])->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[9]
                    ]);
                }

                                 // insert ingredient 4
                if(!empty($csvLine[10])){
                     dump($csvLine[10]);
                    $ingredient5 = $ingredients->where('name',$csvLine[10])->first()->id;
                  	dump($ingredient5);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[11]
                    ]);
                }

        }
    
    }
}