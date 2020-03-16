<?php

namespace Domain\Customer\Events;

use Spatie\EventSourcing\ShouldBeStored;

final class CustomerCreatedEvent implements ShouldBeStored 
{
    /** @var string $first_name */
    public $first_name;

    /** @var string $last_name */
    public $last_name;

    /** @var string $email */
    public $email;

    /** @var string $phone_number */
    public $phone_number;

    /** @var string $uuid */
    public $uuid;

    /**
     * @param string $uuid
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $phone_number
     */
    public function __construct(string $uuid, string $first_name, string $last_name, string $email, string $phone_number)
    {
        $this->uuid = $uuid;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone_number = $phone_number;
    }
}