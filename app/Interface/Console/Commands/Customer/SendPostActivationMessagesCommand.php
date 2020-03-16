<?php

namespace Interfaces\Console\Commands\Customer;

use Domain\Customer\CustomerAggregateRoot;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\MessageData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class SendPostActivationMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:post-activation-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send post activation messages to customers.';

    /**
     * @var \Domain\Customer\Repositories\CustomerContract $customer
     */
    protected $customer;

    /** 
     * Create a new command instance.
     * @param \Domain\Customer\Repositories\CustomerContract $customer
     *
     * @return void
     */
    public function __construct(\Domain\Customer\Repositories\CustomerContract $customer)
    {
        parent::__construct();
        $this->customer = $customer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->customer->sendActivationMessages();
        
        $this->info('Post activation messages sent.');
    }
}