<?php

namespace App\Console\Commands;


use App\FoodIngredients;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'values:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->findAverageAndSd();

        $this->findTotalCarbon();
    }


    public function findAverageAndSd(){

        // get ingredients
        $ingredients = FoodIngredients::all();

        // iterate through each ingredient
        foreach($ingredients as $ingredient){


            $ingredient = $this->findAverage($ingredient);


            $ingredient = $this->findStandardDeviation($ingredient);


            $ingredient->save();
        }
    }

    public function findAverage($ingredient){
                    // find average
            $totalCarbon = 0;
            foreach($ingredient->foodSources()->get() as $foodSource){
                $totalCarbon += $foodSource->kgCO2e_per_kg_food;
            }
            dump($ingredient->name);
            $this->average = $totalCarbon /  count($ingredient->foodSources()->get());
            $ingredient->average_kgCO2e_per_kg_food = $this->average;

            return $ingredient;
    }

    
    public function findStandardDeviation($ingredient){

            // find standard deviation
            $sum = 0;
            foreach($ingredient->foodSources()->get() as $foodSource){
                $sum += pow(($foodSource->kgCO2e_per_kg_food - $this->average), 2);
            }
            $standardDeviation = pow(( $sum / count($ingredient->foodSources()->get())), 0.5);
            if($standardDeviation == 0){
                $ingredient->standard_deviation = null;
            }
           else{
               $ingredient->standard_deviation = $standardDeviation;
           }
           return $ingredient;
    }


    public function findTotalCarbon(){

                // reset cartbon to zero
        DB::table('meals')->update(['total_kgCO2e'=>0]);

        $mealsI = DB::table('meals_ingredients')
            ->join('meals', 'meals.id',"=", 'meals_ingredients.meal_id')
            ->join('food_ingredients', 'food_ingredients.id',"=",'meals_ingredients.ingredient_id')
            ->select('meals_ingredients.mass_of_ingredient_in_grams', 'meals.name', 'food_ingredients.average_kgCO2e_per_kg_food')
            ->get();

        foreach($mealsI as $mealIngredient){

            // get current carbon
            $currentCarbon = DB::table('meals')->where('name', '=', $mealIngredient->name )->select('total_kgCO2e')->first()->total_kgCO2e;
        
            // if null convert to 0
            $currentCarbon = ($currentCarbon === null) ? 0 : $currentCarbon;
    
            // add carbon to existing value
            $totalCarbon = ($mealIngredient->average_kgCO2e_per_kg_food * $mealIngredient->mass_of_ingredient_in_grams /1000 )+ $currentCarbon;
            
            // update 
            DB::table('meals')->where('name', '=', $mealIngredient->name )->update(['total_kgCO2e' => $totalCarbon ]);
   
  
        }
    }
}
