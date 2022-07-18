<?php

namespace App\Console;

use App\Console\Commands\AddPhoneCreditsCommand;
use App\Console\Commands\BetaFeatureCommand;
use App\Console\Commands\CreateBroadcastMessageCommand;
use App\Console\Commands\CurrentSummaryNotificationCommand;
use App\Console\Commands\InactivateVaultsCommand;
use App\Console\Commands\PruneInactiveUsersCommand;
use App\Console\Commands\SendLowBalanceMailCommand;
use App\Console\Commands\SentDepositInfoToUserCommand;
use App\Console\Commands\StatisticsCommand;
use App\Console\Commands\TriggerNextRatioNotificationsCommand;
use App\Console\Commands\TriggerStateNotificationCommand;
use App\Console\Commands\UpdateFixedIntervalPriceCommand;
use App\Console\Commands\UpdateLoanSchemeCommand;
use App\Console\Commands\UpdateVaultDataCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Horizon\Console\StatusCommand;

class Kernel extends ConsoleKernel
{
	protected $commands = [
		UpdateVaultDataCommand::class,
		UpdateLoanSchemeCommand::class,
		UpdateFixedIntervalPriceCommand::class,
		PruneInactiveUsersCommand::class,
		CurrentSummaryNotificationCommand::class,
		StatisticsCommand::class,
		InactivateVaultsCommand::class,
		CreateBroadcastMessageCommand::class,
		AddPhoneCreditsCommand::class,
		BetaFeatureCommand::class,
		StatusCommand::class,
		SendLowBalanceMailCommand::class,
		TriggerNextRatioNotificationsCommand::class,
		TriggerStateNotificationCommand::class,
	];

	protected function schedule(Schedule $schedule): void
	{
		$schedule->command(UpdateVaultDataCommand::class)
			->everyFiveMinutes()
			->withoutOverlapping();
		$schedule->command(UpdateLoanSchemeCommand::class)
			->daily();
		$schedule->command(UpdateFixedIntervalPriceCommand::class)
			->withoutOverlapping()
			->everyFiveMinutes();
		$schedule->command(PruneInactiveUsersCommand::class)
			->weekly();
		$schedule->command(CurrentSummaryNotificationCommand::class)
			->hourlyAt(2);
		$schedule->command(StatisticsCommand::class)
			->dailyAt('23:59');
		$schedule->command(InactivateVaultsCommand::class)
			->hourlyAt(32);

		$schedule->command(SentDepositInfoToUserCommand::class)
			->everyTwoMinutes()
			->withoutOverlapping();

		$schedule->command(SendLowBalanceMailCommand::class)
			->everyFourHours()
			->withoutOverlapping()
			->name('send-low-balance-mail');

		// send out notification commands
		$schedule->command(TriggerNextRatioNotificationsCommand::class)
			->everyFiveMinutes()
			->withoutOverlapping()
			->name('trigger-next-ratio-notifications');
		$schedule->command(TriggerStateNotificationCommand::class)
			->everyFiveMinutes()
			->withoutOverlapping()
			->name('trigger-vault-state-notifications');
	}

	protected function commands(): void
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
