<?php

namespace Domain\Customer\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class MessageSentToCustomerEvent implements ShouldBeStored 
{
    /** @var string $customer_id */
    public $customer_id;

    /** @var string $type */
    public $type;

    /** @var string $content */
    public $content;

    /** @var array $meta_data */
    public $meta_data;

    /**
     * @param string $customer_id
     * @param string $type
     * @param string $content
     * @param array $meta_data
     */
    public function __construct(string $customer_id, string $type, string $content, array $meta_data = [])
    {
        $this->customer_id = $customer_id;
        $this->type = $type;
        $this->content = $content;
        $this->meta_data =  $meta_data;
    }
}