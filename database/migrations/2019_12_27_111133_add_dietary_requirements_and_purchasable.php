<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDietaryRequirementsAndPurchasable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->string('dietary_requirements');
        });
        Schema::table('food_products', function (Blueprint $table){
            $table->string('dietary_requirements');
        });
        Schema::table('meals', function (Blueprint $table){
            $table->string('dietary_requirements');
        });
        Schema::table('food_items_sizes', function (Blueprint $table){
            $table->boolean('purchasable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->dropColumn('dietary_requirements');
        });
        Schema::table('food_products', function (Blueprint $table){
            $table->dropColumn('dietary_requirements');
        });
        Schema::table('meals', function (Blueprint $table){
            $table->dropColumn('dietary_requirements');
        });
        Schema::table('food_items_sizes', function (Blueprint $table){
            $table->dropColumn('purchasable');
        });
    }
}
