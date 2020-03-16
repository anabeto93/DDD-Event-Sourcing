<?php

namespace Domain\Customer\ValueObjects;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class MessageData extends DataTransferObject
{
    /** @var string $customer_id */
    public $customer_id;

    /** @var string $type */
    public $type;

    /** @var string $content */
    public $content;

    /** @var array $meta_data */
    public $meta_data;

    public function fromRequest(Request $request): self
    {
        return new self([
            'customer_id' => $request->input('customer_id'),
            'type' => $request->input('type'),
            'content' => $request->input('content'),
            'meta_data' => $request->input('meta_data') ?: [],
        ]);
    }
}