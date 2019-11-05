<?php

namespace App\Console\Commands;


use App\FoodIngredients;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\MealComponenets;
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


    private $meals;

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

        $this->loadObjects();

        $this->findIngredientsData();

        $this->findCaloriesData();

        $this->findFoodProductsData();

      //  $this->findMealsData();

    }


    public function loadObjects(){

        $this->ingredients = FoodIngredients::all();

        $this->meals = Meals::with('mealsIngredients')->get();

        $this->foodCalories = Calories::get();

    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findIngredientsData(){

        foreach($this->ingredients as $ingredient){

            $ingredient = $this->findAverage($ingredient);

            $ingredient = $this->findStandardDeviation($ingredient);
        }

        $this->findIngredientsCalories();

        $this->findCarbonPerCalorie();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findAverage($ingredient){

        // find average
        $totalCarbon = 0;
        //   dump($ingredient->name);
        foreach($ingredient->foodSources()->get() as $foodSource){
         
            $totalCarbon += $foodSource->kgCO2e_per_kg_food;
        }

       dump(count($ingredient->foodSources()->get()));
        $ingredient->average_kgCO2e_per_kg_food = $totalCarbon /  count($ingredient->foodSources()->get());


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
            $sum += pow(($foodSource->kgCO2e_per_kg_food - $ingredient->average_kgCO2e_per_kg_food), 2);
        }
        $standardDeviation = pow(( $sum / count($ingredient->foodSources()->get())), 0.5);
        if($standardDeviation == 0){
            $ingredient->standard_deviation = null;
        }
       else{
           $ingredient->standard_deviation = $standardDeviation;
       }

       $ingredient->save();
       return $ingredient;
    }


    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function findFoodProductsData(){

        $foodProducts = FoodProducts::all();

        $foodProducts->each(function($foodProduct){
            $cumulativeTotalCarbon = 0;
            $cumulativeTotalMass = 0;
            $foodProduct->foodProductIngredient()->each(function($foodProductIngredient) use ($cumulativeTotalCarbon, $cumulativeTotalMass){
                $cumulativeTotalCarbon += $foodProductIngredient->ingredient()->average_kgCO2e_per_kg_food *
                $foodProductIngredient->ratio;
                $cumulativeTotalMass += $foodProductIngredient->ratio;
                
            });
            dump($cumulativeTotalCarbon);
            dump($cumulativeTotalMass);

            $foofProduct->average_kgCO2e_per_kg_food = $cumulativeTotalCarbon / $cumulativeTotalMass;
        });
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

$cumulativeCalories = 0;

        foreach($mealsI as $mealIngredient){

            // get current carbon
            $currentCarbon = DB::table('meals')->where('name', '=', $mealIngredient->name )->select('total_kgCO2e')->first()->total_kgCO2e;
        
            // if null convert to 0
            $currentCarbon = ($currentCarbon === null) ? 0 : $currentCarbon;

    
            // add carbon to existing value
            $totalCarbon = ($mealIngredient->average_kgCO2e_per_kg_food * $mealIngredient->mass_of_ingredient_in_grams /1000 )+ $currentCarbon;

            
            // update 
            DB::table('meals')
                ->where('name', '=', $mealIngredient->name )
                ->update([
                    'total_kgCO2e' => $totalCarbon

                     ]);

        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findMealAverageCarbon(){

        // get meals_ingredients

        foreach($this->meals->toArray() as $meal) {

            $cumulativeMealMass = 0;

                foreach($meal['meals_ingredients'] as $mealI) {
                   
                      $cumulativeMealMass += $mealI['mass_of_ingredient_in_grams'] /1000;
                };


                $averageCarbon = $meal['total_kgCO2e'] / $cumulativeMealMass;

                 // update 
                DB::table('meals')->where('id', '=', $meal['id'] )->update([
                    'average_carbon' => $averageCarbon,
                    'mass' => round($cumulativeMealMass * 1000),

                ]);

        };

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findCaloriesData(){


        $this->findIngredientsCalories();

        $this->findCarbonPerCalorie();

        $this->findMealCalories();

        $this->findMealCarbonPerCalorie();

    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findIngredientsCalories(){


        $this->ingredients->each(function($ingredient) {


            $this->foodCalories->each(function($fc) use ($ingredient){
    
                if(strpos(strtolower($fc->food), strtolower($ingredient->name)) > -1){
         
                    $ingredient->calories_per_g = $fc->calories_per_g;

                    $ingredient->save();
                }
            });

        });
    }

    public function findCarbonPerCalorie(){


        $this->ingredients->each(function($ingredient) {
        if(!empty($ingredient->calories_per_g)){
            $ingredient->carbon_per_calorie = $ingredient->average_kgCO2e_per_kg_food / $ingredient->calories_per_g;
            $ingredient->save();
        }

        });

    }

    public function findMealCalories(){
  
        $this->meals->each(function($meal){

            $mealIngredients = $meal->mealsIngredients();
            $meal->calories = 0;
            $cumulativeCalories = 0;

            $mealIngredients->each(function($mealI) use($cumulativeCalories, $meal){

                $ingredient = $mealI->ingredient;

                if(!empty($ingredient->calories_per_g)){

                    // find calories
                    $cumulativeCalories = $cumulativeCalories+ $ingredient->calories_per_g * $mealI->mass_of_ingredient_in_grams;
                }

                $meal->calories += round($cumulativeCalories);

            });

            $meal->save();
        });
    }

    public function findMealCarbonPerCalorie(){

        $this->meals->each(function($meal){

            $meal->gCO2e_per_calorie = $meal->total_kgCO2e * 1000 / $meal->calories;
            $meal->save();
        });
    }


    
}
