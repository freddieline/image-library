<?php

namespace App\Console\Commands;


use App\FoodIngredients;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\MealComponenets;
use App\Meals;
use App\FoodProducts;
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

      //  $this->findCaloriesData();

        $this->findFoodProductsData();

        $this->findMealsData();

    }


    public function loadObjects(){

        $this->ingredients = FoodIngredients::all();

        $this->meals = Meals::with('mealComponents')->get();

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
            $this->cumulativeTotalCarbon = 0;
            $this->cumulativeTotalMass = 0;
            $foodProduct->foodProductsIngredients()->each(function($foodProductIngredient) {
                $this->cumulativeTotalCarbon += $foodProductIngredient->ingredient()->first()->average_kgCO2e_per_kg_food *
                $foodProductIngredient->ratio;
                $this->cumulativeTotalMass += $foodProductIngredient->ratio;
            });

            $foodProduct->average_kgCO2e_per_kg_food = $this->cumulativeTotalCarbon / $this->cumulativeTotalMass;
            $foodProduct->save();
        });
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function findMealsData(){

        $meals = Meals::with('mealComponents.ingredient')
                    ->with('mealComponents.foodProduct');

        $meals->each(function($meal){
            $this->cumulativeTotalMass = 0;
            $this->cumulativeTotalCarbon = 0;
            $meal->mealComponents()->each(function($mealComponent){
               
                if($mealComponent->ingredient()->exists()){
             
                    $carbon = $mealComponent->ingredient()->first()->average_kgCO2e_per_kg_food;
                    $mass = $mealComponent->mass_in_grams / 1000;
                   
                    $this->cumulativeTotalCarbon += $carbon * $mass;
                    $this->cumulativeTotalMass += $mealComponent->mass_in_grams;
                }
                else if ($mealComponent->foodProduct()->exists()){
           
                    $this->cumulativeTotalCarbon += $mealComponent->foodProduct()->first()->average_kgCO2e_per_kg_food * 
                        $mealComponent->mass_in_grams / 1000;
                    $this->cumulativeTotalMass += $mealComponent->mass_in_grams;
                }

            });

            $meal->total_kgCO2e = $this->cumulativeTotalCarbon;
            $meal->average_carbon = $this->cumulativeTotalCarbon * 1000 / $this->cumulativeTotalMass;
            $meal->mass = $this->cumulativeTotalMass;
            $meal->save();
        });

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

            $mealIngredients = $meal->mealsComponents();
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
