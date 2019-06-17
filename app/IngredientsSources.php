<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientsSources extends Model
{
    protected $table  = "food_ingredients_sources";
    protected $fillable = ['food_source_id', 'food_ingredient_id', 'created_at', 'updated_at'];
}
