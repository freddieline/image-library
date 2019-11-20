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
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
               	$ingredient5 = 0;
             
                // insert ingredient 1
                $ingredient1 = $ingredients->where('name',$csvLine[1])->first()->id;
                dump($ingredient1);
                	FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient1,
                        'ratio' => (integer)$csvLine[2]
                    ]);

                // insert ingredient 2
                if(!empty($csvLine[3])){
                    $ingredient2 = $ingredients->where('name',$csvLine[3])->first()->id;
                      dump($ingredient2);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient2,
                        'ratio' => (integer)$csvLine[4]
                    ]);
                }
                else{
                    dump($csvLine[3]);
                }

                // insert ingredient 3
                if(!empty($csvLine[5])){
                    $ingredient3 = $ingredients->where('name',$csvLine[5])->first()->id;
                      dump($ingredient3);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient3,
                        'ratio' => (integer)$csvLine[6]
                    ]);
                }
                else{
                    dump($csvLine[5]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[7])){
                    $ingredient4 = $ingredients->where('name',$csvLine[7])->first()->id;
                  	dump($ingredient4);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[8]
                    ]);
                }
                else{
                    dump($csvLine[7]);
                }

                                 // insert ingredient 4
                if(!empty($csvLine[9])){
                    $ingredient5 = $ingredients->where('name',$csvLine[9])->first()->id;
                  	dump($ingredient5);
                    FoodProductsIngredients::updateOrCreate([
                        'food_product_id' => $foodProduct->id,
                        'ingredient_id' =>  $ingredient4,
                        'ratio' => (integer)$csvLine[10]
                    ]);
                }
                else{
                    dump($csvLine[9]);
                }

        }
    
    }
}