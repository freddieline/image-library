<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MealsIngredients extends Model
{
    protected $table  = "meals";
    protected $fillable = ['meal_id','ingredient_id','mass_of_ingredient_in_grams', 'created_at', 'updated_at'];
}
