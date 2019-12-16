<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPortionSizeToIngrdients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_ingredients', function (Blueprint $table){
            $table->string('portion_type')->nullable();
            $table->integer('portion_size')->nullable();
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
            $table->dropColumn('portion_type');
               $table->dropColumn('portion_size');
        });
    }
}
