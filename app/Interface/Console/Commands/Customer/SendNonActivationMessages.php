<?php

namespace Interfaces\Console\Commands\Customer;

use Illuminate\Console\Command;

class SendNonActivationMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:non-activation-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send non-activation messages to customers.';

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
        $this->customer->sendNonActivationMessages();
        
        $this->info('Non-Activation messages sent.');
    }
}