<?php

namespace App\Console\Commands;


use App\FoodIngredients;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\MealsIngredients;
use App\Meals;
use App\Calories;

class FindValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'values:find';

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

        $this->findIngredientsData();

        $this->findMealsData();

        $this->findCaloriesData();
    }




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findIngredientsData(){

        // get ingredients
        $ingredients = FoodIngredients::all();

        // iterate through each ingredient
        foreach($ingredients as $ingredient){


            $ingredient = $this->findAverage($ingredient);


            $ingredient = $this->findStandardDeviation($ingredient);


            $ingredient->save();
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findMealsData(){

        $this->findTotalCarbon();

        $this->findMealAverageCarbon();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findMealAverageCarbon(){

        // get meals_ingredients
        $meals = Meals::with('mealsIngredients')->get()->toArray();

        foreach($meals as $meal) {

            $cumulativeMealMass = 0;

                foreach($meal['meals_ingredients'] as $mealI) {
                    var_dump($mealI);
                      $cumulativeMealMass += $mealI['mass_of_ingredient_in_grams'] /1000;
                };

                $averageCarbon = $meal['total_kgCO2e'] / $cumulativeMealMass;

                 // update 
                DB::table('meals')->where('id', '=', $meal['id'] )->update(['average_carbon' => $averageCarbon ]);

        };

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findCaloriesData(){

        $this->findIngredientsCalories();

    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findIngredientsCalories(){
        $ingredients = FoodIngredients::get();
        $foodCalories = Calories::get();

        $ingredients->each(function($ingredient)use ($foodCalories){


            $foodCalories->each(function($fc) use ($ingredient){
    
                if(strpos(strtolower($fc->food), strtolower($ingredient->name)) > -1){
         
                    $ingredient->calories_per_g = $fc->calories_per_g;

                    $ingredient->save();
                }
            });
            dump($ingredient->calories_per_g );
            dump($ingredient->name);

        });
    }


    
}
