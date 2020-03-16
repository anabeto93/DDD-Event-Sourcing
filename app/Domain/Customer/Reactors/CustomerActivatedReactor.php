<?php

namespace Domain\Customer\Reactors;

use Domain\Customer\Models\Customer;
use Domain\Customer\Events\TransactionAddedEvent;
use Spatie\EventSourcing\EventHandlers\EventHandler;
use Spatie\EventSourcing\EventHandlers\HandlesEvents;
use App\Events\Customer\CustomerActivatedEvent as DomainCustomerActivated;

final class CustomerActivatedReactor implements EventHandler
{
    use HandlesEvents;

    public function onTransactionAdded(TransactionAddedEvent $event) 
    {
        //make any other decisions regarding the transactions performed by the customer here
        $customer = Customer::uuid($event->customer_id);

        //supposed to be a reactor
        if ($customer->transactions->count() == 1) { //that is their very first transaction
            //activate the customer
            $customer->activated_at = now()->toDateTimeString();
            $customer->save();
            event(new DomainCustomerActivated($customer));
        }
    }
}