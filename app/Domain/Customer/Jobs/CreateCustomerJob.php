<?php

namespace Domain\Customer\Jobs;

use Domain\Customer\ValueObjects\CustomerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Domain\Customer\ValueObjects\CustomerData $customer */
    public $customer;

    /**
     * Create a new job instance.
     * @param \Domain\Customer\ValueObjects\CustomerData $customer
     * @return void
     */
    public function __construct(CustomerData $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
    }
}