<?php

namespace Domain\Customer\Events;

use Spatie\EventSourcing\ShouldBeStored;
use Domain\Customer\ValueObjects\TransactionData;

final class TransactionAddedEvent implements ShouldBeStored 
{
    /** @var TransactionData $transaction */
    public $transaction;


    /**
     * 
     * @param string $customer_id
     * @param TransactionData $transaction
     */
    public function __construct(TransactionData $transaction)
    {
        $this->transaction = $transaction;
    }
}