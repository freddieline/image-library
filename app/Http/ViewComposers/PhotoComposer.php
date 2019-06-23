<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 19/07/2017
 * Time: 15:37
 */

namespace App\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use App\FoodIngredients;
use App\Meals;


class PhotoComposer {

    private $_photosDirectory;
    private $_files;
    private $_photoFileNames;

    /**
     * @param View $View
     */
    public function compose( View $View )
    {

        $ingredients = FoodIngredients::all()->toArray();
        $meals = Meals::all()->toArray();

        // Alias for if the user is signed in
        $View->with('food_ingredients', $ingredients);
        $View->with('meals', $meals );
    }


}