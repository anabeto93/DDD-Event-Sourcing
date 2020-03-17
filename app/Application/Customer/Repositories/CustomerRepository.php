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


    public function activated($duration=null)
    {
        if ($duration) {
            return Customer::activated()->whereDate('created_at', '>=', now()->subDays($duration)->setTime(0, 0, 0)->toDateTimeString())->cursor();
        }

        return Customer::activated()->cursor();
    }

    public function nonActivated($duration=null)
    {
        if ($duration) {
            return Customer::nonActivated()->whereDate('created_at', '>=', now()->subDays($duration)->setTime(0, 0, 0)->toDateTimeString())->cursor();
        }

        return Customer::nonActivated()->cursor();
    }

    public function sendActivationMessages(): void
    {
        $days_in_3_months_ago = now()->subMonths(3)->diffInDays(now());
        $customers = $this->activated($days_in_3_months_ago);// accurate number of days over the last 3 months

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
                } elseif ($days >= 21 && $days < 90) { // on day 21, but all stops after 90 days
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

    public function sendNonActivationMessages(): void
    {
        $days_in_3_months_ago = now()->subMonths(3)->diffInDays(now());
        $customers = $this->nonActivated($days_in_3_months_ago);//to get the accurate number of days

        DB::beginTransaction();

        try {
            $customers->each(function($user) {
                $days = now()->diffInDays($user->created_at);

                if ($days >= 3 && $days < 14) {
                    //check if the message was already sent, else send it
                    if (!($user->messageSent('FirstNonActivationSMS') || $user->messageSent('FirstNonActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.nonactivated.first');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'sms', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'FirstNonActivationSMS']]));
                        if (!$user->messageSent('FirstNonActivationNotification')) {
                            $message = config('expresspay.notifications.nonactivated.first');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'FirstNonActivationNotification']]));
                        }
                        $customer->persist();
                    }
                } elseif ($days >= 14 && $days < 28) {
                    if (!($user->messageSent('SecondNonActivationSMS') || $user->messageSent('SecondNonActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.nonactivated.second');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'sms', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'SecondNonActivationSMS']]));
                        if (!$user->messageSent('SecondNonActivationNotification')) {
                            $message = config('expresspay.notifications.nonactivated.second');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'SecondNonActivationNotification']]));
                        }
                        $customer->persist();
                    }
                } elseif ($days >= 28 && $days < 45) {
                    if (!($user->messageSent('ThirdNonActivationSMS') || $user->messageSent('ThirdNonActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.nonactivated.third');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'sms', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'ThirdNonActivationSMS']]));
                        if (!$user->messageSent('ThirdNonActivationNotification')) {
                            $message = config('expresspay.notifications.nonactivated.third');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'ThirdNonActivationNotification']]));
                        }
                        $customer->persist();
                    }
                } elseif ($days >= 45 && $days < 90) {//all this stops after 90 days
                    if (!($user->messageSent('FourthNonActivationSMS') || $user->messageSent('FourthNonActivationNotification'))) {
                        $customer = CustomerAggregateRoot::retrieve($user->uuid);
                        $message = config('expresspay.messages.nonactivated.fourth');
                        $message = str_replace('{first_name}', $user->first_name, $message);
                        $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'sms', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'FourthNonActivationSMS']]));
                        if (!$user->messageSent('FourthNonActivationNotification')) {
                            $message = config('expresspay.notifications.nonactivated.fourth');
                            $message = str_replace('{first_name}', $user->first_name, $message);
                            $customer->sendMessage(new MessageData(['customer_id' => $user->uuid, 'type' => 'flash', 'content' => $message, 'meta_data' => ['type' => 'nonactivated', 'class' => 'FourthNonActivationNotification']]));
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