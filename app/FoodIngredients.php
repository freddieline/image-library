<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodIngredients extends Model
{
    protected $table  = "food_ingredients";
    protected $fillable = ['name', 'calories_per_g', 'carbon_per_calorie', 'average_kgCO2e_per_kg_food', 'is_liquid', 'portion_type', 
      "portion_size"];

    public function foodSources(){
        return $this->belongsToMany('App\FoodSources','food_ingredients_sources', 'food_ingredient_id', 'food_source_id');
    }

    public function foodItemsSizes(){
        return $this->hasMany('App\FoodItemsSizes', 'ingredient_id');
    }

}
