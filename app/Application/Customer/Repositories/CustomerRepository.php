<?php

namespace App\Customer\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Domain\Customer\Models\Customer;
use Domain\Customer\CustomerAggregateRoot;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;
use Domain\Customer\Repositories\CustomerContract;

class CustomerRepository implements CustomerContract
{
    public function create(CustomerData $customer): void
    {
        DB::beginTransaction();

        try {
            $customer->uuid = Str::orderedUuid()->toString();
            $croot = CustomerAggregateRoot::retrieve($customer->uuid)->create($customer)->persist();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    public function addTransaction(TransactionData $transaction): void
    {
        DB::beginTransaction();

        try {
            $customer = Customer::where('email', $transaction->customer_email)->orWhere('uuid', $transaction->customer_id)->firstOrFail();
            $transaction->customer_id = $customer->uuid;
            $croot = CustomerAggregateRoot::retrieve($customer->uuid)->addTransaction($transaction)->persist();
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    public function find($uuid) 
    {
        return CustomerAggregateRoot::retrieve($uuid);
    }

    public function findByEmail(string $email) 
    {
        $customer = Customer::where('email', $email)->firstOrFail();

        return CustomerAggregateRoot::retrieve($customer->uuid);
    }
}