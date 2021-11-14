<?php

namespace App\Console;

use App\Console\Commands\UpdateFixedIntervalPriceCommand;
use App\Console\Commands\UpdateVaultDataCommand;
use App\Console\Commands\UpdateLoanSchemeCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
	    UpdateVaultDataCommand::class,
	    UpdateLoanSchemeCommand::class,
	    UpdateFixedIntervalPriceCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command(UpdateVaultDataCommand::class, ['--max=100'])->everyFiveMinutes();
         $schedule->call(UpdateLoanSchemeCommand::class)->daily();
         $schedule->call(UpdateFixedIntervalPriceCommand::class)->everyThirtyMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
