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
    protected $fillable = ['uuid', 'first_name', 'last_name', 'email', 'phone_number'];

    public static function createWithAttributes(array $attributes) : Customer 
    {
        $attributes['uuid'] = isset($attributes['uuid']) ? $attributes['uuid'] : Str::orderedUuid()->toString();
        //broadcast an event that the customer has been created
        $customer = new CustomerData($attributes);
        event(new CustomerCreatedEvent($customer->uuid, $customer->first_name, $customer->last_name, $customer->email, $customer->phone_number));

        return static::uuid($attributes['uuid']);
    }

    public function addTransaction(TransactionData $transaction) 
    {
        $transaction->customer_id = $this->uuid;
        event(new TransactionAddedEvent($transaction->customer_id, $transaction->amount, $transaction->currency, $transaction->timestamp));
    }

    public static function uuid(string $uuid): ?Customer
    {
        return static::where('uuid', $uuid)->first();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'customer_id', 'uuid');
    }

    public function scopeActivated($query)
    {
        return $query->whereNotNull('activated_at');
    }

    public function scopeNonActivated($query)
    {
        return $query->whereNull('activated_at');
    }

    public function activatedMessages()
    {
        return $this->hasMany(ActivatedMessage::class, 'customer_id', 'uuid')->latest();
    }

    public function nonActivatedMessages()
    {
        return $this->hasMany(NonActivatedMessage::class, 'customer_id', 'uuid')->latest();
    }

    public function messages()
    {
        return $this->hasMany(CustomerMessage::class, 'customer_id', 'uuid')->latest();
    }

    public function messageSent(string $class): bool 
    {
        return $this->activatedMessages()->where('class', $class)->exists() || $this->nonActivatedMessages()->where('class', $class)->exists();
    }
}
