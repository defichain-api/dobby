<?php

namespace App\Console;

use App\Console\Commands\CurrentSummaryNotificationCommand;
use App\Console\Commands\PruneInactiveUsersCommand;
use App\Console\Commands\StatisticsCommand;
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
		PruneInactiveUsersCommand::class,
		CurrentSummaryNotificationCommand::class,
		StatisticsCommand::class,
	];

	protected function schedule(Schedule $schedule): void
	{
		$schedule->command(UpdateVaultDataCommand::class, ['--max=100'])
			->everyFiveMinutes()
			->withoutOverlapping();
		$schedule->command(UpdateLoanSchemeCommand::class)
			->daily();
		$schedule->command(UpdateFixedIntervalPriceCommand::class)
			->everyFiveMinutes();
		$schedule->command(PruneInactiveUsersCommand::class)
			->weekly();
		$schedule->command(StatisticsCommand::class)
			->dailyAt('23:59');
	}

	protected function commands(): void
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
