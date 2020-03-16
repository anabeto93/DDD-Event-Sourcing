<?php

namespace App\Events\Customer;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomerActivatedEvent
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
        Log::info('Customer activated', ['customer' => $customer->toArray()]);
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
