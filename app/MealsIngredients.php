<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MealsIngredients extends Model
{
    protected $table  = "meals_ingredients";
    protected $fillable = ['meal_id','ingredient_id','mass_of_ingredient_in_grams', 'created_at', 'updated_at'];


    public function ingredient()
    {
        return $this->belongsTo('App\FoodIngredients', 'ingredient_id');
    }
}
