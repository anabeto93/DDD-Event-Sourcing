<?php

namespace Domain\Customer\ValueObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class TransactionData extends DataTransferObject 
{
    /** @var string $customer_id */
    public $customer_id;

    /** @var int|null $id */
    public $id;

    /** @var string $amount */
    public $amount;

    /** @var string $currency */
    public $currency;

    /** @var string|null $created_at */
    public $created_at;

    public static function fromRequest(Request $request): self
    {
        return new self([
            'customer_id' => $request->input('customer_id'),
            'id' => $request->input('id'),
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'created_at' => $request->input('created_at')
        ]);
    }
}