<?php

namespace Domain\Customer\Events;

use Spatie\EventSourcing\ShouldBeStored;
use Domain\Customer\ValueObjects\TransactionData;

final class TransactionAddedEvent implements ShouldBeStored 
{
    /** @var TransactionData $transaction */
    public $transaction;

    /** @var string $customer_id */
    public $customer_id;


    /**
     * @param TransactionData $transaction
     * @param string $customer_id
     */
    public function __construct(TransactionData $transaction, string $customer_id)
    {
        $this->transaction = $transaction;
        $this->customer_id = $customer_id;

        //just to be clear, add it to the transaction
        $this->transaction->customer_id = $customer_id;
    }
}