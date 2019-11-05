<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MealComponents extends Model
{
	protected $table  = "meal_components";
    protected $fillable = ['meal_id','ingredient_id','mass_in_grams', 'created_at', 'updated_at'];


    public function ingredient(){
        return $this->belongsTo('App\FoodIngredients', 'ingredient_id');
    }

    public function foodProduct(){
        return $this->belongsTo('App\FoodProducts', 'food_product_id');
    }

    public function meal(){
        return $this->belongsTo('App\Meal', 'meal_id');
    }
}
