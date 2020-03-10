<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Customer\CustomerCreatedEvent;
use Support\SMS\SMSContract;

class SendWelcomeSMS implements ShouldQueue
{
    /** @var SMSContract $sms */
    public $sms;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SMSContract $sms)
    {
        $this->sms = $sms;
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
        $message = config('expresspay.notifications.welcome');
        $this->sms->flash($event->customer->phone_number, $message);
    }
}
