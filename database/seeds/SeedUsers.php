<?php

use Illuminate\Database\Seeder;

class SeedUsers extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Set the date
        $date = date( 'Y-m-d H:i:s' );

        // Insert me as the default user
        DB::table( 'users' )->insert([
            [
                'name'          => 'Michael Farrance',
                'email'         => 'michael.farrance@grandvisual.com',
                'password'      => bcrypt( 'P0pc0rn!' ),
                'created_at'    => $date,
                'updated_at'    => $date
            ]
        ]);

    }
}
