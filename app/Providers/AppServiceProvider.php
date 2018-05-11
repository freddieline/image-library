<?php

namespace App\Providers;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Queue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Logger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Queue::after(function (JobProcessed $event)   {

            $payload = json_decode( $event->job->getRawBody() );

            $payload = unserialize( $payload->data->command );

            Logger::updateOrCreate(
                [ 'image-filename' => $payload->photo ],
                [
                    'sent' => false,
                    'deleted' => false,
                    'failed' => true
                ]
            );

        });

        Queue::failing(function (JobFailed $event) {


        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
