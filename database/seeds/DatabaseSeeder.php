<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FoodSourcesSeeder::class);
        $this->call(FoodIngredientsSeeder::class);
        $this->call(IngredientsSourcesSeeder::class);
        $this->call(MealsSeeder::class);
        $this->call(CaloriesSeeder::class);
    }
}
