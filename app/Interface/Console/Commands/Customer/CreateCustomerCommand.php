<?php

namespace Interfaces\Console\Commands\Customer;

use Domain\Customer\ValueObjects\CustomerData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:create {--first_name= : The customer first name} {--last_name= : The customer last name} {--email= : The customer\'s email} {--phone_number= : The customer\'s phone number.} {--customer= : The customer details object.}';

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
        $customer = json_decode($this->option('customer'), true);

        if (!$customer || !is_array($customer)) {
            $customer = [];
            $customer['first_name'] = $this->option('first_name') ?: $this->ask('Enter First name: ');
            $customer['last_name'] = $this->option('last_name') ?: $this->ask('Enter Last name: ');
            $customer['email'] = $this->option('email') ?: $this->ask('Enter Email: ');
            $customer['phone_number'] = $this->option('phone_number') ?: $this->ask('Enter Phone number: ');
        }
        //validate the customer object
        $validator = Validator::make($customer, [
            'first_name' => ['bail','required','string','min:3'],
            'last_name' => ['bail', 'required', 'string', 'min:3'],
            'email' => ['bail', 'required', 'email', 'unique:customers,email'],
            'phone_number' => ['bail', 'required', 'digits_between:10,12'],
        ]);

        if ($validator->fails()) {
            $this->info('Customer not created. See error messages below:');
        
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $newC = new CustomerData($customer);

        $this->customer->create($newC);

        $this->info('Customer Created.');
    }
}
