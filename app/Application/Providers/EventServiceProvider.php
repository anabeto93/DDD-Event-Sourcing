<?php

namespace App\Providers;

use App\Events\Customer\CustomerActivatedEvent;
use App\Listeners\SendWelcomeSMS;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendWelcomeNotification;
use App\Events\Customer\CustomerCreatedEvent;
use App\Events\Customer\NotificationSentToCustomerEvent;
use App\Events\Customer\SMSSentToCustomerEvent;
use App\Listeners\SendActivationCongratulatorySMS;
use App\Listeners\SendNotification;
use App\Listeners\SendSMS;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerCreatedEvent::class => [
            SendWelcomeNotification::class,
            SendWelcomeSMS::class,
        ],
        CustomerActivatedEvent::class => [
            SendActivationCongratulatorySMS::class,
        ],
        NotificationSentToCustomerEvent::class => [
            SendNotification::class,
        ],
        SMSSentToCustomerEvent::class => [
            SendSMS::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
