<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Domain\Customer\Models\Customer;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'uuid' => Str::orderedUuid()->toString(),
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'email' => $faker->email,
        'phone_number' => $faker->e164PhoneNumber,
    ];
});

$factory->state(Customer::class, 'activated', function(Faker $faker) {
    return [
        'activated_at' => now()->toDateTimeString(),
    ];
});

$factory->state(Customer::class, 'nonactivated', function(Faker $faker) {
    return [
        'activated_at' => null,
    ];
});
