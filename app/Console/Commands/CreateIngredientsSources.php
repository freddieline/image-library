<?php

namespace App\Console\Commands;

use App\FoodIngredients;
use App\FoodSources;
use App\IngredientsSources;
use Illuminate\Console\Command;

class CreateIngredientsSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingredients-sources:update';

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
        $foodSources = FoodSources::all();

        $ingredients->each(function($ingredient) use ($foodSources){

            $foodSources->each(function($source) use ($ingredient){

                // check if ingredient name is included in food source name
                if(strtolower($source->food) === strtolower($ingredient->name) ){
                    IngredientsSources::firstOrCreate([
                        'food_source_id' => $source->id,
                        'food_ingredient_id' => $ingredient->id
                    ]);
                }

                // check if ingredient is included in food source name
                $tags = explode(",",$source->tags);
                foreach($tags as $tag){

                    // check ingredient name against each tag
                    if( strtolower($ingredient->name) === strtolower($tag)){
                        IngredientsSources::firstOrCreate([
                            'food_source_id' => $source->id,
                            'food_ingredient_id' => $ingredient->id
                        ]);
                    }
                    // check category name against each tage
                    if(!empty($ingredient->category)){
                        if( strtolower($ingredient->category) === strtolower($tag)){
                            IngredientsSources::firstOrCreate([
                                'food_source_id' => $source->id,
                                'food_ingredient_id' => $ingredient->id
                            ]);
                        }
                    }
                }
            });

        });

    }
}
