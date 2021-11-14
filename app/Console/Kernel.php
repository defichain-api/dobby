<?php

namespace App\Console;

use App\Console\Commands\UpdateLoanSchemeCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
	    UpdateLoanSchemeCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->call(UpdateLoanSchemeCommand::class)->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
