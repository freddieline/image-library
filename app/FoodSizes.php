<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodSizes extends Model
{
       protected $table  = "food_sizes";
       protected $fillable = ['id', 'description', 'created_at', 'updated_at'];
}
