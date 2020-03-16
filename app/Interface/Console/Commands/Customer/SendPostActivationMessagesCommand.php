<?php

namespace Interfaces\Console\Commands\Customer;

use Domain\Customer\CustomerAggregateRoot;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\MessageData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SendPostActivationMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:post-activation-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send post activation messages to customers.';

    /**
     * @var \Domain\Customer\Repositories\CustomerContract $customer
     */
    protected $customer;

    /** 
     * Create a new command instance.
     * @param \Domain\Customer\Repositories\CustomerContract $customer
     *
     * @return void
     */
    public function __construct(\Domain\Customer\Repositories\CustomerContract $customer)
    {
        parent::__construct();
        $this->customer = $customer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $customers = $this->customer->activated();

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
                }
            }
        });
        \Log::info('Running From Cron');
        $this->info('Post activation messages sent.');
    }
}