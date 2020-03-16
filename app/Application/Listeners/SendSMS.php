<?php

namespace App\Listeners;

use Support\SMS\SMSContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Customer\SMSSentToCustomerEvent;

class SendSMS
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
     * @param  SMSSentToCustomerEvent $event
     * @return void
     */
    public function handle(SMSSentToCustomerEvent $event)
    {
        Log::info('Sending SMS to Customer', ['notification' => $event->message, 'phone_number' => $event->customer->phone_number]);
        $this->sms->send($event->customer->phone_number, $event->message);
    }
}
