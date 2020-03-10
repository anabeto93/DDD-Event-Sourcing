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
    {dd(new CustomerCreatedEvent($customerData));
        $this->recordThat(new CustomerCreatedEvent($customerData));

        return $this;
    }

    public function addTransaction(TransactionData $transactionData) 
    {
        $this->recordThat(new TransactionAddedEvent($transactionData, $this->uuid));
    }
}