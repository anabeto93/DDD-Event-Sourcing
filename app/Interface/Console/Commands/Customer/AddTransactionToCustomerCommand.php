<?php

namespace Interfaces\Console\Commands\Customer;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Domain\Customer\ValueObjects\CustomerData;
use Domain\Customer\ValueObjects\TransactionData;

class AddTransactionToCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:transaction {--transaction_id= : The transaction id} {--amount= : The transaction amount} {--currency= : The transaction currency} {--customer_id= : The customer\'s id} {--customer_email= : The customer\'s email} {--timestamp= : Transaction timestamp} {--transaction= : The transaction payload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a transaction to the customer';

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
        $transaction = json_decode($this->option('transaction'), true);

        if (!$transaction || !is_array($transaction)) {
            $transaction = [];
            $transaction['transaction_id'] = $this->option('transaction_id') ?: $this->ask('Transaction Id: ');
            $transaction['customer_id'] = ($this->option('customer_id') || $this->option('customer_email')) ?: $this->ask('Enter customer\'s id: ');
            $transaction['customer_email'] = ($this->option('customer_id') || $this->option('customer_email')) ?: $this->ask('Enter customer\'s email: ');
            $transaction['amount'] = $this->option('amount') ?: $this->ask('Transaction amount: ');
            $transaction['currency'] = $this->option('currency') ?: $this->ask('Transaction currency: ');
            $transaction['timestamp'] = $this->option('timestamp') ?: $this->ask('Transaction timestamp: ');
        }

        //validate the transaction object
        $validator = Validator::make($transaction, [
            'transaction_id' => ['bail', 'required'],
            'customer_id' => ['bail', 'string' ],
            'customer_email' => ['bail', 'string'],
            'amount' => ['bail', 'digits_between:3,12'],
            'currency' => ['bail', 'string', 'size:3'],
            'timestamp' => ['bail', 'string'],
        ]);

        if ($validator->fails()) {
            $this->info('Transaction not added. See error messages below:');
        
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1;
        }

        $newT = new TransactionData($transaction);

        $this->customer->addTransaction($newT);

        $this->info('Transaction added.');
    }
}
