<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FoodIngredientsSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_ingredients_sources', function (Blueprint $table) {
            $table->integer('food_source_id')->unsigned();
            $table->integer('food_ingredient_id')->unsigned();
            $table->foreign('food_source_id')->references('id')->on('food_sources');
            $table->foreign('food_ingredient_id')->references('id')->on('food_ingredients');
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
        Schema::dropIfExists('food_ingredients_sources');
    }
}
