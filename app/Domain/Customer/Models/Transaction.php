<?php

namespace Domain\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function customer() 
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'uuid');
    }
}
