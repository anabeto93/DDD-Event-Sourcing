<?php

namespace Domain\Customer\Projectors;

use Domain\Customer\Models\CustomerMessage;
use Domain\Customer\Models\ActivatedMessage;
use Spatie\EventSourcing\Projectors\Projector;
use Domain\Customer\Models\NonActivatedMessage;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Domain\Customer\Events\MessageSentToCustomerEvent;
use Illuminate\Support\Facades\Log;

class CustomerMessagesProjector implements Projector
{
    use ProjectsEvents;


    public function onMessageSentToCustomer(MessageSentToCustomerEvent $event)
    {dd($event);
        $props = [
            'format' => $event->type,
            'content' => $event->content,
            'customer_id' => $event->customer_id,
        ];

        Log::info('Message To Customer', ['message' => $props]);

        if (!empty($event->meta_data) && array_key_exists('type', $event->meta_data) && in_array($event->meta_data, ['activated', 'nonactivated'])) {
            $props['class'] = $event->meta_data['class'];
            
            if ($event->meta_data['type'] == 'activated') {
                $message = ActivatedMessage::create($props);
            } else {
                $message = NonActivatedMessage::create($props);
            }
        } else {
            $message = CustomerMessage::create($props);
        }
    }
}