<?php

namespace Interfaces\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Interfaces\Console\Commands\Customer\CreateCustomerCommand::class,
        \Interfaces\Console\Commands\Customer\AddTransactionToCustomerCommand::class,
        \Interfaces\Console\Commands\Customer\SendPostActivationMessagesCommand::class,
        \Interfaces\Console\Commands\Customer\SendNonActivationMessagesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('customer:post-activation-messages')->dailyAt('09:00');
        $schedule->command('customer:non-activation-messages')->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('app/Interface/routes/console.php');
    }
}
