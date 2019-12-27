<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use App\Meals;
use Illuminate\Support\Collection;
use App\FoodIngredients;
use App\FoodProducts;
use App\MealComponents;

class MealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reader = Reader::createFromPath(storage_path().'/Dishes.csv', 'r');

        $ingredients = FoodIngredients::all();

        $foodProducts = FoodProducts::all();

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {


                $meal = App\Meals::updateOrCreate([
                    'name' => $csvLine[0],
                ],[
                    'dietary_requirements' => '',
                    'tags' => $csvLine[1],
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
             
           
                $ingredient1 = $ingredients->where('name',strtolower($csvLine[2]))->first();

                if(empty($ingredient1)){
                    $foodProduct1 = $foodProducts->where('name',strtolower($csvLine[2]))->first()->id;
                    MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'food_product_id' =>  $foodProduct1
                    ],
                    [ 
                        'mass_in_grams' => (integer)$csvLine[3],

                    ]);
                }
                else{
                     MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient1->id
                    ],
                    [
                        'mass_in_grams' => (integer)$csvLine[3]
                    ]);
         
                }
       

                if(!empty($csvLine[4])){
                    $ingredient2 = $ingredients->where('name',strtolower($csvLine[4]))->first();

                    if(empty($ingredient2)){
                        dump($csvLine[4]);
                        $foodProduct2 = $foodProducts->where('name',strtolower($csvLine[4]))->first()->id;
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct2
                        ],
                        [
                            'mass_in_grams' => (integer)$csvLine[5]
                        ]);
                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient2->id,
                        ],
                        [
                            'mass_in_grams' => (integer)$csvLine[5]
                        ]);
                    }
                }
                else{
                    dump($csvLine[4]);
                }

                if(!empty($csvLine[6])){


                    $ingredient3 = $ingredients->where('name',strtolower($csvLine[6]))->first();
                    if(empty($ingredient3)){
                        dump($csvLine[6]);
                        $foodProduct = $foodProducts->where('name',strtolower($csvLine[6]))->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct
                        ],
                        [
                            'mass_in_grams' => (integer)$csvLine[7]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' => $ingredient3->id
                        ],
                        [
                            'mass_in_grams' => (integer)$csvLine[7]
                        ]);
                    }
                    
                }
                else{
                    dump($csvLine[6]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[8])){
                    $ingredient4 = $ingredients->where('name',strtolower($csvLine[8]))->first();
                    if(empty($ingredient4)){
                        dump($csvLine[8]);
                        $foodProduct = $foodProducts->where('name',strtolower($csvLine[8]))->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct
                        ],[
                            'mass_in_grams' => (integer)$csvLine[9]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient4->id
                        ],[
                            'mass_in_grams' => (integer)$csvLine[9]
                        ]);
                    }
                }
                else{
                    dump($csvLine[8]);
                }

                // insert ingredient 5
                if(!empty($csvLine[10])){

                    $ingredient5 = $ingredients->where('name',strtolower($csvLine[10]))->first();

                    if(empty($ingredient5)){

                        $foodProduct = $foodProducts->where('name',strtolower($csvLine[10]))->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct
                        ],[
                            'mass_in_grams' => (integer)$csvLine[11]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient5->id
                        ],[
                            'mass_in_grams' => (integer)$csvLine[11]
                        ]);
                    }
                }
                else{
                    dump($csvLine[9]);
                }

        }
    
    }
}
