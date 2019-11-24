<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodItemsSizes extends Model
{
   protected $table  = "food_items_sizes";
       protected $fillable = ['id','ingredient_id','food_product_id','food_size_id','mass_in_grams', 'created_at', 'updated_at'];

       
    public function ingredient(){
        return $this->belongsTo('App\FoodIngredients', 'ingredient_id');
    }

    public function foodProduct(){
        return $this->belongsTo('App\FoodProducts', 'food_product_id');
    }


    public function foodSize(){
        return $this->belongsTo('App\FoodSizes', 'food_size_id');
    }
}
