<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meals;
use App\MealsIngredients;
use App\FoodIngredients;

class MealsController extends Controller
{

    /**
     * @param Request $request
     * 
     */
    public function getMeal(Request $request){


        $mealIngredients = MealsIngredients::
                    where('meal_id' ,'=', $request->id)
                    ->with('ingredient.foodSources')
                    ->get()
                    ->toArray()
                ;


        $results = [];
        foreach($mealIngredients as $mealI) {
            $sd = empty($mealI['ingredient']['standard_deviation']) ? null : (100 * $mealI['ingredient']['standard_deviation'] / $mealI['ingredient']['average_kgCO2e_per_kg_food']);
            $results[] = [
                'ingredient' => $mealI['ingredient']['name'],
                'mass_in_grams' => $mealI['mass_of_ingredient_in_grams'],
                'average_kgCO2e_per_kg_food' => round($mealI['ingredient']['average_kgCO2e_per_kg_food'],2),
                'ingredient_kgCO2' => round($mealI['ingredient']['average_kgCO2e_per_kg_food'] * $mealI['mass_of_ingredient_in_grams'] / 1000,2),
                'sd_percent' =>  round($sd ,2),
                'food_sources' => $mealI['ingredient']['food_sources']
            ];
        }

        $meal = Meals::where('id' ,'=',$request->id)->first()->toArray();
        $meal['ingredients'] = $results;
        
        return array( 'meal' => $meal, 'meal_ingredients' => $results);
    }
}
