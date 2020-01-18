<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseAverage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->dropColumn('average_kgCO2e_per_kg_food');
        });
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->float('average_kgCO2e_per_kg_food',9,6);
         });
        Schema::table('food_sources', function (Blueprint $table){
            $table->dropColumn('kgCO2e_per_kg_food');
        });
          Schema::table('food_sources', function (Blueprint $table){
            $table->float('kgCO2e_per_kg_food',9,6);
        });
         Schema::table('food_ingredients', function (Blueprint $table){
            $table->dropColumn('average_kgCO2e_per_kg_food');
        });
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->float('average_kgCO2e_per_kg_food',9,6);
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
