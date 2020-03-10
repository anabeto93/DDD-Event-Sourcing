<?php

namespace Domain\Customer\Events;

use Domain\Customer\ValueObjects\CustomerData;
use Spatie\EventSourcing\ShouldBeStored;

final class CustomerCreatedEvent implements ShouldBeStored 
{
    /** @var CustomerData $customer */
    public $customer;


    /**
     * @param CustomerData $customer
     */
    public function __construct(CustomerData $customer)
    {
        $this->customer = $customer;
    }
}