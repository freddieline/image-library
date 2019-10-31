<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodProductsIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('food_products_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('food_product_id')->unsigned();
            $table->integer('ingredient_id')->unsigned();
            $table->integer('ratio');
            $table->foreign('food_product_id')->references('id')->on('food_products');
            $table->foreign('ingredient_id')->references('id')->on('food_ingredients');
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
        Schema::dropIfExists('food_products_ingredients');
    }
}