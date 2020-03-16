<?php

namespace Domain\Customer\Reactors;

use Domain\Customer\Models\Customer;
use Domain\Customer\Events\CustomerCreatedEvent;
use Spatie\EventSourcing\EventHandlers\EventHandler;
use Spatie\EventSourcing\EventHandlers\HandlesEvents;
use App\Events\Customer\CustomerCreatedEvent as CustomerCreatedSystemEvent;

final class CustomerCreatedReactor implements EventHandler
{
    use HandlesEvents;

    public function onCustomerCreated(CustomerCreatedEvent $event, string $aggregateUuid) 
    {
        $customer = Customer::uuid($aggregateUuid);

        event(new CustomerCreatedSystemEvent($customer));//broadcast this upon creating the customer
    }
}