<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FoodIngredients;
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

        // get meals_ingredients
        $mealsT = Meals::
            with('mealsIngredients.ingredient.foodSources')
            ->get()
            ->toArray();

        $meals['meals'] = $mealsT;

        $meals = json_encode($meals);

        file_put_contents(storage_path('meals.json'), stripslashes($meals));


        // get food_ingredients
        $ingredientsT = FoodIngredients::
            with('foodSources')
            ->get()
            ->toArray();

        $ingredients['ingredients'] = $ingredientsT;

        $ingredients = json_encode($ingredients);

        file_put_contents(storage_path('ingredients.json'), stripslashes($ingredients));
    }
}
