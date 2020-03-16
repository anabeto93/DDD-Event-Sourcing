<?php

namespace Domain\Customer\Projectors;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\Transaction;
use Domain\Customer\ValueObjects\CustomerData;
use Spatie\EventSourcing\Projectors\Projector;
use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use App\Events\Customer\CustomerCreatedEvent as DomainCustomerCreated;
use App\Events\Customer\CustomerActivatedEvent as DomainCustomerActivated;

class CustomerTransactionsProjector implements Projector
{
    use ProjectsEvents;

    public function onCustomerCreated(CustomerCreatedEvent $event) 
    {
        //this is where the customer is created
        $props = [
            'uuid' => $event->uuid,
            'first_name' => $event->first_name,
            'last_name' => $event->last_name,
            'email' => $event->email,
            'phone_number' => $event->phone_number,
        ];

        $customer = Customer::create($props);
        event(new DomainCustomerCreated($customer));//broadcast this upon creating the customer
    }

    public function onTransactionAdded(TransactionAddedEvent $event) 
    {
        $data = [
            'customer_id' => $event->customer_id,
            'amount' => $event->amount,
            'currency' => $event->currency,
            'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s', $event->timestamp),
        ];
        Log::info('Adding a transaction', ['details' => $data]);

        $transaction = Transaction::create($data);
        //make any other decisions regarding the transactions performed by the customer here
        $customer = Customer::uuid($event->customer_id);

        if ($customer->transactions->count() > 1) { //that is their very first transaction
            //activate the customer
            $customer->activated_at = now();
            $customer->save();
            event(new DomainCustomerActivated($customer));
        }
    }
}
