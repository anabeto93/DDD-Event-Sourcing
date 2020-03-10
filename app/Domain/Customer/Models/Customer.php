<?php

namespace Domain\Customer\Models;

use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;

class Customer extends Model
{
    public static function createWithAttributes(array $attributes) : Customer 
    {
        $attributes['uuid'] = Str::orderedUuid()->toString();
        //broadcast an event that the customer has been created
        $customer = new CustomerData($attributes);
        event(new CustomerCreatedEvent($customer));

        return static::uuid($attributes['uuid']);
    }

    public function addTransaction(TransactionData $transaction) 
    {
        event(new TransactionAddedEvent($transaction, $this->attributes['uuid']));
    }

    public static function uuid(string $uuid): ?Customer
    {
        return static::where('uuid', $uuid)->first();
    }
}
