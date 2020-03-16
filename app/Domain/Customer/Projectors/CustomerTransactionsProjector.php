<?php

namespace Domain\Customer\Projectors;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Domain\Customer\Models\Customer;
use Domain\Customer\Models\Transaction;
use Spatie\EventSourcing\Projectors\Projector;
use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

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
    }
}
