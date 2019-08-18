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
        $meals = Meals::
            with('mealsIngredients.ingredient.foodSources')
            ->get()
            ->toArray();

            $meals= json_encode($meals);

        file_put_contents(storage_path('meals.json'), stripslashes($meals));


        // get food_ingredients
        $ingredients = FoodIngredients::
            with('foodSources')
            ->get()
            ->toArray();

        $ingredients = json_encode($ingredients);

        file_put_contents(storage_path('ingredients.json'), stripslashes($ingredients));
    }
}
