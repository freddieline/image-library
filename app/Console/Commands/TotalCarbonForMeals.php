<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Meals;
use Illuminate\Support\Facades\DB;

class TotalCarbonForMeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'total-carbon-meals:find';

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
                $totalCarbon = $mealIngredient->average_kgCO2e_per_kg_food * $mealIngredient->mass_of_ingredient_in_grams + $currentCarbon;
                
                // update 
                DB::table('meals')->where('name', '=', $mealIngredient->name )->update(['total_kgCO2e' => $totalCarbon ]);
   
  
        }
    }
}
