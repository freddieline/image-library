<?php

namespace App\Providers;
use Faker\Provider\File;
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

            \File::move(storage_path("app/public/photos/") . $payload->photo, storage_path("app/public/sent/"). $payload->photo );

            Logger::updateOrCreate(
                [ 'image-filename' => $payload->photo ],
                [
                    'sent' => true,
                    'deleted' => false,
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
