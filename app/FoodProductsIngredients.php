<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodProductsIngredients extends Model
{
   protected $table  = "food_products_ingredients";
   protected $fillable = ['food_product_id', 'ingredient_id', 'ratio', 'created_at', 'updated_at'];
}
