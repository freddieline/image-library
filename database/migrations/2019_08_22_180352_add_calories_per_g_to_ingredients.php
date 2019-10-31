<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaloriesPerGToIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->float('calories_per_g',9,5)->nullable();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->integer('mass')->nullable();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->integer('calories')->nullable();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->float('gCO2e_per_calorie',9,5)->nullable();
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
            $table->dropColumn('calories_per_g');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('mass');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('calories');
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('gCO2e_per_calorie');
        });
    }
}
