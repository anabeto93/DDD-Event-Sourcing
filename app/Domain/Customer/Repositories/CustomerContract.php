<?php

namespace Domain\Customer\Repositories;

use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;

interface CustomerContract 
{
    /** Create a new customer
     * @param CustomerData $customer
     * @return void
     */
    public function create(CustomerData $customer): void;

    /**
     * Find a customer by id
     * @param string $uuid
     * @return \Domain\Customer\CustomerAggregateRoot|null
     */
    public function find($uuid);

    /**
     * Find a customer by email
     * @param string $email
     * @return \Domain\Customer\CustomerAggregateRoot|null
     */
    public function findByEmail(string $email);

    /**
     * @param TransactionData $transaction
     * @return void
     */
    public function addTransaction(TransactionData $transaction): void;
}