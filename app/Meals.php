<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $table = "meals";
    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function mealsIngredients(){
        return $this->belongsToMany('App\FoodIngredients','meals_ingredients', 'meal_id', 'ingredient_id');
    }
}
