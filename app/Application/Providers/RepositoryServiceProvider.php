<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //not necessarily a repository
        $this->app->bind(\Support\SMS\SMSContract::class, \Support\SMS\InfoBipSMS::class);
        $this->app->bind(\Domain\Customer\Repositories\CustomerContract::class, \App\Customer\Repositories\CustomerRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
