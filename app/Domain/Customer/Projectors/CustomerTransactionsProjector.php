<?php

namespace Domain\Customer\Projectors;

use App\Domain\Customer\Models\Customer;
use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use App\Events\Customer\CustomerCreatedEvent as DomainCustomerCreated;
use App\Events\Customer\CustomerActivatedEvent as DomainCustomerActivated;
use Domain\Customer\Models\Transaction;

class CustomerTransactionsProjector implements Projector
{
    use ProjectsEvents;

    public function onCustomerCreated(CustomerCreatedEvent $event) 
    {
        //this is where the customer is created
        $customer = Customer::create($event->customer->toArray());
        event(new DomainCustomerCreated($customer));//broadcast this upon creating the customer
    }

    public function onTransactionAdded(TransactionAddedEvent $event) 
    {
        $transaction = Transaction::create($event->transaction->toArray());
        //make any other decisions regarding the transactions performed by the customer here
        $customer = Customer::uuid($event->transaction->customer_id);

        if ($customer->transactions->count() == 1) { //that is their very first transaction
            //activate the customer
            $customer->activated_at = now();
            $customer->save();
            event(new DomainCustomerActivated($customer));
        }
    }
}
