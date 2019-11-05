<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;
use App\Meals;
use Illuminate\Support\Collection;
use App\FoodIngredients;
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

        $results = $reader->getRecords();

        foreach ($results as $csvLine) {


                $meal = App\Meals::updateOrCreate([
                'name' => $csvLine[0]
                
                ]);

                $ingredient1 = 0;
                $ingredient2 = 0;
                $ingredient3 = 0;
                $ingredient4 = 0;
             
                // insert ingredient 1
                $ingredient1 = $ingredients->where('name',$csvLine[1])->first()->id;
                MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient1,
                        'mass_in_grams' => (integer)$csvLine[4]
                    ]);

                // insert ingredient 2
                if(!empty($csvLine[3])){
                    $ingredient2 = $ingredients->where('name',$csvLine[3])->first()->id;
                    MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient2,
                        'mass_in_grams' => (integer)$csvLine[4]
                    ]);
                }
                else{
                    dump($csvLine[3]);
                }

                // insert ingredient 3
                if(!empty($csvLine[5])){
                    $ingredient3 = $ingredients->where('name',$csvLine[5])->first()->id;
                    MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient3,
                        'mass_in_grams' => (integer)$csvLine[6]
                    ]);
                }
                else{
                    dump($csvLine[5]);
                }
                
                 // insert ingredient 4
                if(!empty($csvLine[7])){
                    $ingredient4 = $ingredients->where('name',$csvLine[7])->first()->id;
                    MealComponents::updateOrCreate([
                        'meal_id' => $meal->id,
                        'ingredient_id' =>  $ingredient4,
                        'mass_in_grams' => (integer)$csvLine[8]
                    ]);
                }
                else{
                    dump($csvLine[7]);
                }

        }
    
    }
}
