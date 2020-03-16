<?php

namespace App\Listeners;

use Support\SMS\SMSContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Customer\NotificationSentToCustomerEvent;

class SendNotification
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
     * @param  NotificationSentToCustomerEvent  $event
     * @return void
     */
    public function handle(NotificationSentToCustomerEvent $event)
    {
        Log::info('Sending Notification to Customer', ['notification' => $event->message, 'phone_number' => $event->customer->phone_number]);
        $this->sms->flash($event->customer->phone_number, $event->message);
    }
}
