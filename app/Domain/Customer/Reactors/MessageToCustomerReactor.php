<?php

namespace Domain\Customer\Reactors;

use Domain\Customer\Models\Customer;
use Domain\Customer\Events\CustomerCreatedEvent;
use Spatie\EventSourcing\EventHandlers\EventHandler;
use Spatie\EventSourcing\EventHandlers\HandlesEvents;
use Domain\Customer\Events\MessageSentToCustomerEvent;
use App\Events\Customer\NotificationSentToCustomerEvent as NotificationSentToCustomer;
use App\Events\Customer\SMSSentToCustomerEvent as SMSSentToCustomer;

final class MessageToCustomerReactor implements EventHandler
{
    use HandlesEvents;

    public function onMessageSentToCustomer(MessageSentToCustomerEvent $event)
    {
        $customer = Customer::uuid($event->customer_id);

        if ($event->type == 'flash') {
            //send a flash message
            event(new NotificationSentToCustomer($customer, $event->content));
        } else {
            //send an sms
            event(new SMSSentToCustomer($customer, $event->content));
        }
    }
}