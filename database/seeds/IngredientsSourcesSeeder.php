<?php

use Illuminate\Database\Seeder;
use App\FoodIngredients;
use App\FoodSources;
use Illuminate\Support\Collection;
use App\IngredientsSources;

class IngredientsSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
