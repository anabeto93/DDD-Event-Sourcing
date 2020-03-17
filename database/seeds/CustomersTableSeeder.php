<?php

use Illuminate\Database\Seeder;
use Domain\Customer\Models\Customer;
use Domain\Customer\ValueObjects\CustomerData;
use App\Customer\Repositories\CustomerRepository;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = new CustomerRepository();

        if (app()->environment(['local', 'staging'])) {
            Customer::unguard();
            //create 3 
            factory(Customer::class, 3)->states('activated')->make()->each(function($user) use($customer) {
                $cd = $user->toArray();
                unset($cd['activated_at']);
                $customer->create(new CustomerData($cd));
                $actual = Customer::where('email', $cd['email'])->first();
                $actual->activated_at = $user->toArray()['activated_at'];
                $actual->save();
            });

            //create 5 non-activated
            factory(Customer::class, 5)->states('nonactivated')->make()->each(function($user) use($customer) {
                $cd = $user->toArray();
                unset($cd['activated_at']);
                $customer->create(new CustomerData($cd));
                $actual = Customer::where('email', $cd['email'])->first();
                $actual->activated_at = $user->toArray()['activated_at'];
                $actual->save();
            });

            //create 3 customers who are 1 week old
            foreach([3, 7, 14, 21, 28, 45, 90] as $day) {
                factory(Customer::class, 3)->states('nonactivated')->make([
                    'created_at' => now()->subDays($day),
                ])->each(function($user) use($customer) {
                    $cd = $user->toArray();
                    unset($cd['activated_at']);
                    unset($cd['created_at']);
                    unset($cd['updated_at']);
                    $customer->create(new CustomerData($cd));
                    $actual = Customer::where('email', $cd['email'])->first();
                    $actual->activated_at = $user->toArray()['activated_at'];
                    $actual->created_at = $user->toArray()['created_at'];
                    $actual->updated_at = $user->toArray()['created_at'];
                    $actual->save();
                });

                factory(Customer::class, 3)->states('activated')->make([
                    'created_at' => now()->subDays($day),
                ])->each(function($user) use($customer) {
                    $cd = $user->toArray();
                    unset($cd['activated_at']);
                    unset($cd['created_at']);
                    unset($cd['updated_at']);
                    $customer->create(new CustomerData($cd));
                    $actual = Customer::where('email', $cd['email'])->first();
                    $actual->activated_at = $user->toArray()['activated_at'];
                    $actual->created_at = $user->toArray()['created_at'];
                    $actual->updated_at = $user->toArray()['created_at'];
                    $actual->save();
                });

                //create customers who are more than 3 months old
                factory(Customer::class, 5)->states('nonactivated')->make([
                    'created_at' => now()->subDays($day+90),
                ])->each(function($user) use($customer) {
                    $cd = $user->toArray();
                    unset($cd['activated_at']);
                    unset($cd['created_at']);
                    unset($cd['updated_at']);
                    $customer->create(new CustomerData($cd));
                    $actual = Customer::where('email', $cd['email'])->first();
                    $actual->activated_at = $user->toArray()['activated_at'];
                    $actual->created_at = $user->toArray()['created_at'];
                    $actual->updated_at = $user->toArray()['created_at'];
                    $actual->save();
                });

                factory(Customer::class, 5)->states('activated')->make([
                    'created_at' => now()->subDays($day+90),
                ])->each(function($user) use($customer) {
                    $cd = $user->toArray();
                    unset($cd['activated_at']);
                    unset($cd['created_at']);
                    unset($cd['updated_at']);
                    $customer->create(new CustomerData($cd));
                    $actual = Customer::where('email', $cd['email'])->first();
                    $actual->activated_at = $user->toArray()['activated_at'];
                    $actual->created_at = $user->toArray()['created_at'];
                    $actual->updated_at = $user->toArray()['created_at'];
                    $actual->save();
                });
            }

            Customer::reguard();
        }
    }
}
