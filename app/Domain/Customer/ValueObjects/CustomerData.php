<?php

namespace Domain\Customer\ValueObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class CustomerData extends DataTransferObject
{
    /** @var string $first_name */
    public $first_name;

    /** @var string $last_name */
    public $last_name;

    /** @var string $email */
    public $email;

    /** @var string $phone_number */
    public $phone_number;

    public static function fromRequest(Request $request): self
    {
        return new self([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);
    }
}