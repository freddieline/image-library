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


        $meal = MealsIngredients::
                    where('meal_id' ,'=', $request->id)
                    ->with('ingredient.foodSources')
                    ->get()
                    ->toArray()
                ;
        
        return array( 'data'=> $meal);
    }
}
