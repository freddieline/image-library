<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoodSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unique_id')->unique();
            $table->string('food');
            $table->float('kgCO2e_per_kg_food',9,2);
            $table->string('origin_location')->nullable();
            $table->string('tags');
            $table->string('source_title')->nullable();
            $table->string('authors');
            $table->string('publisher')->nullable();
            $table->string('link');
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
        Schema::dropIfExists('food_sources');
    }
}
