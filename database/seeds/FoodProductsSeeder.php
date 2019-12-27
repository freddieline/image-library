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
                    'dietary_requirements' => $csvLine[1],
                    'category' => $csvLine[2],
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
               	$ingredient5 = 0;
             
                // insert ingredient 1
                $ingredient1 = $ingredients->where('name',$csvLine[3])->first()->id;
 
            	FoodProductsIngredients::updateOrCreate([
                    'food_product_id' => $foodProduct->id,
                    'ingredient_id' =>  $ingredient1,
                    'ratio' => (integer)$csvLine[4]
                ]);

                // insert ingredient 2
                if(!empty($csvLine[5])){
                    dump($csvLine[5]);
                    $ingredient2 = $ingredients->where('name',$csvLine[5])->first()->id;
                      dump($ingredient2);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient2,
                        'ratio' => (integer)$csvLine[6]
                    ]);
                }

                // insert ingredient 3
                if(!empty($csvLine[7])){
                    dump($csvLine[7]);
                    $ingredient3 = $ingredients->where('name',$csvLine[7])->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient3,
                        'ratio' => (integer)$csvLine[8]
                    ]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[9])){
                    dump($csvLine[9]);
                    $ingredient4 = $ingredients->where('name',$csvLine[9])->first()->id;
          
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' => $ingredient4,
                        'ratio' => (integer)$csvLine[10]
                    ]);
                }

                                 // insert ingredient 4
                if(!empty($csvLine[11])){
                     dump($csvLine[11]);
                    $ingredient5 = $ingredients->where('name',$csvLine[11])->first()->id;
                  	dump($ingredient5);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[12]
                    ]);
                }

        }
    
    }
}