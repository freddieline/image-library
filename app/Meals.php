<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    protected $table = "meals";
    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function ingredients(){
        return $this->belongsToMany('App\FoodIngredients','meal_components', 'meal_id', 'ingredient_id');
    }
    public function mealsIngredients(){
        return $this->hasMany('App\MealComponents', 'meal_id');
    }
}
