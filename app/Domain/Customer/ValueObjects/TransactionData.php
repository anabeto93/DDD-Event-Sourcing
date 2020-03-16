<?php

namespace Domain\Customer\ValueObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class TransactionData extends DataTransferObject 
{
    /** @var string|null $customer_id */
    public $customer_id;

    /** @var string|null $customer_email */
    public $customer_email;

    /** @var string|int $transaction_id */
    public $transaction_id;

    /** @var string $amount */
    public $amount;

    /** @var string $currency */
    public $currency;

    /** @var string $timestamp */
    public $timestamp;

    public static function fromRequest(Request $request): self
    {
        return new self([
            'customer_id' => $request->input('customer_id'),
            'customer_email' => $request->input('customer_email'),
            'transaction_id' => $request->input('transaction_id'),
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'timestamp' => $request->input('timestamp')
        ]);
    }
}