<?php

namespace Domain\Customer;

use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;
use Spatie\EventSourcing\AggregateRoot;

final class CustomerAggregateRoot extends AggregateRoot
{
    public function create(CustomerData $customerData)
    {
        $this->recordThat(new CustomerCreatedEvent($customerData->uuid, $customerData->first_name, $customerData->last_name, $customerData->email, $customerData->phone_number));

        return $this;
    }

    public function addTransaction(TransactionData $transactionData) 
    {
        $this->recordThat(new TransactionAddedEvent($transactionData));

        return $this;
    }
}