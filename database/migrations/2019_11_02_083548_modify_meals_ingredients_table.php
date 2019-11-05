<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMealsIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('meals_ingredients', 'meal_components');
        Schema::table('meal_components', function (Blueprint $table){
            $table->integer('food_product_id')->unsigned()->nullable();
            $table->foreign('food_product_id')->references('id')->on('food_products');
            $table->integer('ingredient_id')->unsigned()->nullable()->change();
            $table->renameColumn('mass_of_ingredient_in_grams', 'mass_in_grams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_components', function (Blueprint $table){
            $table->dropColumn('food_product_id');
                
        });
        Schema::rename('meal_components', 'meals_ingredients');

    }
}
