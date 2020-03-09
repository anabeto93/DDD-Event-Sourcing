<?php

namespace App\Customer\Repositories;

use Domain\Customer\Models\Customer;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\Repositories\CustomerContract;
use Illuminate\Support\Facades\DB;
use App\Events\Customer\CustomerCreatedEvent as CustomerCreated;

class CustomerRepository implements CustomerContract
{
    public function create(CustomerData $customer): void
    {
        DB::beginTransaction();

        try {
            $nc = new Customer();
            $nc->first_name = $customer->first_name;
            $nc->last_name = $customer->last_name;
            $nc->email = $customer->email;
            $nc->phone_number = $customer->phone_number;
            $nc->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        event(new CustomerCreated($nc));
    }
}