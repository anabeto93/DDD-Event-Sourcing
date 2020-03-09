<?php

namespace Interfaces\Console\Commands\Customer;

use Domain\Customer\ValueObjects\CustomerData;
use Illuminate\Console\Command;

class CreateCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:create {--customer= : The customer details array ?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Customer';

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
        $customer = $this->option('customer');

        if (!$customer || !is_array($customer)) {
            $customer = [];
            $customer['first_name'] = $this->ask('Enter first name: ');
            $customer['last_name'] = $this->ask('Enter last name: ');
            $customer['email'] = $this->ask('Enter email: ');
            $customer['phone_number'] = $this->ask('Enter Phone number: ');
        }

        $newC = new CustomerData($customer);

        dd($newC);
    }
}
