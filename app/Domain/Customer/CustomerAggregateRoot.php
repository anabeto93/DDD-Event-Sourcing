<?php

namespace Domain\Customer;

use Domain\Customer\Events\CustomerCreatedEvent;
use Domain\Customer\Events\MessageSentToCustomerEvent;
use Domain\Customer\Events\TransactionAddedEvent;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\MessageData;
use Domain\Customer\ValueObjects\TransactionData;
use Spatie\EventSourcing\AggregateRoot;

final class CustomerAggregateRoot extends AggregateRoot
{
    public function create(CustomerData $customerData)
    {
        $this->recordThat(new CustomerCreatedEvent($customerData->uuid, $customerData->first_name, $customerData->last_name, $customerData->email, $customerData->phone_number));
        $this->sendMessage(new MessageData(['customer_id' => $customerData->uuid, 'type' => 'sms', 'content' => config('expresspay.messages.welcome'), 'meta_data' => ['class' => 'WelcomeUserSMS', 'type' => 'nonactivated']]));
        $this->sendMessage(new MessageData(['customer_id' => $customerData->uuid, 'type' => 'flash', 'content' => config('expresspay.notifications.welcome'), 'meta_data' => ['class' => 'WelcomeUserNotification', 'type' => 'nonactivated']]));

        return $this;
    }

    public function addTransaction(TransactionData $transactionData) 
    {
        $this->recordThat(new TransactionAddedEvent($transactionData->customer_id, $transactionData->amount, $transactionData->currency, $transactionData->timestamp));

        return $this;
    }

    public function sendMessage(MessageData $message) 
    {
        $this->recordThat(new MessageSentToCustomerEvent($message->customer_id, $message->type, $message->content, $message->meta_data));

        return $this;
    }
}