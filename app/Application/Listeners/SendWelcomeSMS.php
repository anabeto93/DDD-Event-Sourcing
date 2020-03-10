<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Customer\CustomerCreatedEvent;

class SendWelcomeSMS
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
     * @param  CustomerCreatedEvent  $event
     * @return void
     */
    public function handle($event)
    {
        Log::debug('Sending Welcome SMS', ['event' => $event->customer->toArray()]);
    }
}
