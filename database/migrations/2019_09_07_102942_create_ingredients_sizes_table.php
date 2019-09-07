<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientsSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredients_sizes', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('ingredient_id')->unsigned();
            $table->integer('food_size_id')->unsigned();
            $table->foreign('ingredient_id')->references('id')->on('food_ingredients');
            $table->foreign('food_size_id')->references('id')->on('food_sizes');
            $table->integer('mass_of_ingredient_in_grams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients_sizes');
    }
}
