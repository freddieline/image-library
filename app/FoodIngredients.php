<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodIngredients extends Model
{
    protected $table  = "food_ingredients";
    protected $fillable = ['name'];

    public function foodSources(){
        return $this->belongsToMany('App\FoodSources','food_ingredients_sources', 'food_ingredient_id', 'food_source_id');
    }

}
