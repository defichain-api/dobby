<?php

namespace App\Console;

use App\Console\Commands\UpdateVaultDataCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
	    UpdateVaultDataCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command(UpdateVaultDataCommand::class, ['--max=100'])->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
