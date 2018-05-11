<?php

namespace App\Listeners;

use App\Log;
use Carbon\Carbon;
use App\Events\Event;
use Illuminate\Queue\Events\JobFailed;




class EventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(JobFailed $event)
    {
    }
}
