<?php

namespace App\Console\Commands;


use App\FoodIngredients;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FindAverageAndSd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'average-and-sd:find';

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


        $ingredients = FoodIngredients::all();

        // iterate through each ingredient
        foreach($ingredients as $ingredient){

            // find average
            $totalCarbon = 0;
            foreach($ingredient->foodSources()->get() as $foodSource){
                $totalCarbon += $foodSource->kgCO2e_per_kg_food;
            }
            dump($ingredient->name);
            $average = $totalCarbon /  count($ingredient->foodSources()->get());
            $ingredient->average_kgCO2e_per_kg_food = $average;

            // find standard deviation
            $sum = 0;
            foreach($ingredient->foodSources()->get() as $foodSource){
                $sum += pow(($foodSource->kgCO2e_per_kg_food - $average), 2);
            }
            $standardDeviation = pow(( $sum / count($ingredient->foodSources()->get())), 0.5);
            if($standardDeviation == 0){
                $ingredient->standard_deviation = null;
            }
           else{
               $ingredient->standard_deviation = $standardDeviation;
           }
            $ingredient->save();
        }
    }
}
