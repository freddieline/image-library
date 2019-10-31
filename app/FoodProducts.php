<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodProducts extends Model
{
   protected $table  = "food_products";
   protected $fillable = ['name', 'created_at', 'updated_at'];
}
