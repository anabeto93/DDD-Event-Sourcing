<?php

namespace App\Events\Customer;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \Domain\Customer\Models\Customer $customer */
    public $customer;
    /**
     * Create a new event instance.
     * @param \Domain\Customer\Models\Customer $customer
     * @return void
     */
    public function __construct(\Domain\Customer\Models\Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
