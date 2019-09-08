<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientsSizes extends Model
{
   protected $table  = "ingredients_sizes";
       protected $fillable = ['id','ingredient_id','food_size_id','mass_of_ingredient_in_grams', 'created_at', 'updated_at'];

       
    public function ingredient(){
        return $this->belongsTo('App\FoodIngredients', 'ingredient_id');
    }


    public function foodSize(){
        return $this->belongsTo('App\FoodSizes', 'food_size_id');
    }
}
