<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FoodIngredients;
use App\IngredientsSizes;
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
    protected $signature = 'table-values:export';

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
    }

    /**
     * Export meals
     *
     */
    public function exportMeals(){

         // get meals_ingredients
        $mealsT = Meals::
                    with('mealsIngredients.ingredient.foodSources')
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
    public function exportFoodProducts(){

        $foodProductsT = IngredientsSizes::
                            with('foodSize')
                            ->with('ingredient.foodSources')
                            ->get()
                            ->toArray();

        $foodProducts['food_products'] = $foodProductsT;

        $foodProducts = json_encode($foodProducts);

        file_put_contents(storage_path("food_products.json"), stripslashes($foodProducts));

    }
}
