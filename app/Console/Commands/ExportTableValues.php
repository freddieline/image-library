<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FoodIngredients;
use App\FoodProducts;
use App\FoodItemsSizes;
use App\Meals;
use App\Ingredients;
use Illuminate\Support\Facades\Storage;

class ExportTableValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tables:export';

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

        $this->exportMeals();

        $this->exportIngredients();
 
        $this->exportFoodProducts();

        $this->exportFoodSizes();
    }

    /**
     * Export meals
     *
     */
    public function exportMeals(){

         // get meals_ingredients
        $mealsT = Meals::
                    with('mealComponents.ingredient')
                    ->with('mealComponents.foodProduct')
                    ->get()
                    ->toArray()
                ;

        $meals['meals'] = $mealsT;

        $meals = json_encode($meals);

        file_put_contents(storage_path('meals.json'), stripslashes($meals));

    }

    /**
     * Export ingredients
     *
     */
    public function exportIngredients(){

       // get food_ingredients
        $ingredientsT = FoodIngredients::
            with('foodSources')
            ->with('foodItemsSizes.foodSize')
            ->get()
            ->toArray()
        ;
        

        $ingredients['ingredients'] = $ingredientsT;

        $ingredients = json_encode($ingredients);

        file_put_contents(storage_path('ingredients.json'), stripslashes($ingredients));

    }

    /**
     * Export food products
     *
     */
    public function exportFoodSizes(){

        $foodSizesT = FoodItemsSizes::
                            with('foodSize')
                            ->with('foodProduct')
                            ->with('ingredient')
                            ->get()
                            ->toArray();

        $foodSizes['food_sizes'] = $foodSizesT;

        $foodSizes = json_encode($foodSizes);

        file_put_contents(storage_path("food_sizes.json"), stripslashes($foodSizes));

    }

        /**
     * Export food products
     *
     */
    public function exportFoodProducts(){

        $foodProductsT = foodProducts::
                            with('foodItemsSizes.foodSize')
                            ->get()
                            ->toArray();

        $foodProducts['food_products'] = $foodProductsT;

        $foodProducts = json_encode($foodProducts);

        file_put_contents(storage_path("food_products.json"), stripslashes($foodProducts));

    }
}
