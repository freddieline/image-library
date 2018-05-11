<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class PhotoServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        View::composer(  '*', 'App\Http\ViewComposers\PhotoComposer' );

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
