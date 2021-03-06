<?php

namespace Domain\Customer\Models;

use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;

class NonActivatedMessage extends Model
{
    protected $table = 'non_activated_messages';
    
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'uuid', 'customer_id');
    }
}