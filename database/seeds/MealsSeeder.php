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
                'name' => $csvLine[0]
                
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
             
           
                $ingredient1 = $ingredients->where('name',$csvLine[1])->first();

                if(empty($ingredient1)){
                    $foodProduct1 = $foodProducts->where('name',$csvLine[1])->first()->id;
                    MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'food_product_id' =>  $foodProduct1,
                        'mass_in_grams' => (integer)$csvLine[2]
                    ]);
                }
                else{
                     MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient1->id,
                        'mass_in_grams' => (integer)$csvLine[2]
                    ]);
         
                }
       

                if(!empty($csvLine[3])){
                    $ingredient2 = $ingredients->where('name',$csvLine[3])->first();

                    if(empty($ingredient2)){
                        dump($csvLine[3]);
                        $foodProduct2 = $foodProducts->where('name',$csvLine[3])->first()->id;
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct2,
                            'mass_in_grams' => (integer)$csvLine[4]
                        ]);
                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient2->id,
                            'mass_in_grams' => (integer)$csvLine[4]
                        ]);
                    }
                }
                else{
                    dump($csvLine[3]);
                }

                if(!empty($csvLine[5])){


                    $ingredient3 = $ingredients->where('name',$csvLine[5])->first();
                    if(empty($ingredient3)){
                        dump($csvLine[5]);
                        $foodProduct = $foodProducts->where('name',$csvLine[5])->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct,
                            'mass_in_grams' => (integer)$csvLine[6]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' => $ingredient3->id,
                            'mass_in_grams' => (integer)$csvLine[6]
                        ]);
                    }
                    
                }
                else{
                    dump($csvLine[5]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[7])){
                    $ingredient4 = $ingredients->where('name',$csvLine[7])->first();
                    if(empty($ingredient4)){
                        dump($csvLine[7]);
                        $foodProduct = $foodProducts->where('name',$csvLine[7])->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct,
                            'mass_in_grams' => (integer)$csvLine[8]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient4->id,
                            'mass_in_grams' => (integer)$csvLine[8]
                        ]);
                    }
                }
                else{
                    dump($csvLine[7]);
                }

                // insert ingredient 5
                if(!empty($csvLine[9])){

                    $ingredient5 = $ingredients->where('name',$csvLine[9])->first();

                    if(empty($ingredient5)){

                        $foodProduct = $foodProducts->where('name',$csvLine[9])->first()->id;
                         MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'food_product_id' =>  $foodProduct,
                            'mass_in_grams' => (integer)$csvLine[10]
                        ]);

                    }
                    else{
                        MealComponents::updateOrCreate([
                            'meal_id' => $meal->id,
                            'ingredient_id' =>  $ingredient5->id,
                            'mass_in_grams' => (integer)$csvLine[10]
                        ]);
                    }
                }
                else{
                    dump($csvLine[9]);
                }

        }
    
    }
}
