<?php

namespace Domain\Customer\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class TransactionAddedEvent implements ShouldBeStored 
{
    /** @var string $customer_id */
    public $customer_id;

    /** @var string $amount */
    public $amount;

    /** @var string $currency */
    public $currency;

    /** @var string $timestamp */
    public $timestamp;


    /**
     * 
     * @param string $customer_id
     * @param string $amount
     * @param string $currency
     * @param string $timestamp
     */
    public function __construct(string $customer_id, string $amount, string $currency, string $timestamp)
    {
        $this->customer_id = $customer_id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->timestamp = $timestamp;
    }
}