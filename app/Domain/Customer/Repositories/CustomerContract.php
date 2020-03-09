<?php

namespace Domain\Customer\Repositories;

use Domain\Customer\ValueObjects\CustomerData;

interface CustomerContract 
{
    /** Create a new customer
     * @var CustomerData $customer
     * @return void
     */
    public function create(CustomerData $customer): void;
}