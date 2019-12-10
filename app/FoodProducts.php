<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodProducts extends Model
{
	protected $table  = "food_products";
   	protected $fillable = ['name', 'created_at', 'updated_at'];


    public function foodProductsIngredients(){
        return $this->hasMany('App\FoodProductsIngredients', 'food_product_id');
    }


    public function foodItemsSizes(){
        return $this->hasMany('App\FoodItemsSizes', 'food_product_id');
    }
}
