<?php

namespace App\Customer\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Domain\Customer\Models\Customer;
use Domain\Customer\CustomerAggregateRoot;
use Domain\Customer\ValueObjects\MessageData;
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


    public function activated()
    {
        return Customer::activated()->cursor();
    }

    public function nonActivated()
    {
        return Customer::nonActivated()->cursor();
    }

    public function sendActivationMessages(): void
    {
        $customers = $this->activated();

        DB::beginTransaction();

        try {
            $customers->each(function($user) {
                $days = now()->diffInDays($user->activated_at);
                if ($days >= 7 && $days < 14) {//on day 7
                    //check if the message was already sent, else send it
                    if (!($user->messageSent('FirstPostActivationSMS') || $user->messageSent('FirstPostActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.activated.first');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'sms', 'content' => $message, 'meta_data' => ['type' => 'activated', 'class' => 'FirstPostActivationSMS']]));
                        if (!$user->messageSent('FirstPostActivationNotification')) {
                            $message = config('expresspay.notifications.activated.first');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'activated', 'class' => 'FirstPostActivationNotification']]));
                        }
                        $customer->persist();
                    }
    
                } elseif ($days >= 14 && $days < 21) {// on day 14
                    if (!($user->messageSent('SecondPostActivationSMS') || $user->messageSent('SecondPostActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.activated.second');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        
                        if (!$user->messageSent('SecondPostActivationNotification')) {
                            $message = config('expresspay.notifications.activated.second');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'activated', 'class' => 'SecondPostActivationNotification']]));
                        }
                        $customer->persist();
                    }
                } elseif ($days >= 21) { // on day 21
                    if (!($user->messageSent('ThirdPostActivationSMS') || $user->messageSent('ThirdPostActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.activated.third');
                        $message = str_replace('{first_name}', $user->first_name, $message);
    
    
                        
                        if (!$user->messageSent('ThirdPostActivationNotification')) {
                            $message = config('expresspay.notifications.activated.third');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'activated', 'class' => 'ThirdPostActivationNotification']]));
                        }

                        $customer->persist();
                    }
                }
            });
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}