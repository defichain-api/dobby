<?php

namespace App\Console\Commands;

use App\Enum\SummaryInterval;
use App\Models\User;
use App\Notifications\CurrentSummaryTriggerNotification;
use Illuminate\Console\Command;

class CurrentSummaryNotificationCommand extends Command
{
	protected $signature = 'notification:current-summary';
	protected $description = 'Send a summary to all users with preset config';

	public function handle()
	{
		User::withCount('gateways') // select only users with active gateways
		->having('gateways_count', '>', 0)
			->withCount('vaults') // select only active users with a vault
			->having('vaults_count', '>', 0)
			->with('setting')
			->whereHas('setting', function ($query) { // select only users receiving the summary
				return $query->whereIn('summary_interval', [
					SummaryInterval::DAILY_ONCE,
					SummaryInterval::DAILY_TWICE,
					SummaryInterval::DAILY_THRICE,
					SummaryInterval::HOURLY,
				]);
			})
			->chunk(100, function ($users) {
				$users->each(function (User $user) {
					$userSummaryInterval = $user->setting->summary_interval;
					$this->setUsersTimezone($user);

					$sendMessage = match (now()->hour) {
						8 => in_array($userSummaryInterval, [
							SummaryInterval::DAILY_ONCE,
							SummaryInterval::DAILY_TWICE,
							SummaryInterval::DAILY_THRICE,
							SummaryInterval::HOURLY,
						]),
						13 => in_array($userSummaryInterval, [
							SummaryInterval::DAILY_THRICE,
							SummaryInterval::HOURLY,
						]),
						20 => in_array($userSummaryInterval, [
							SummaryInterval::DAILY_TWICE,
							SummaryInterval::DAILY_THRICE,
							SummaryInterval::HOURLY,
						]),
						default => $userSummaryInterval == SummaryInterval::HOURLY
					};

					$sendMessage
					&& $user->notify(new CurrentSummaryTriggerNotification($user->vaults->first()));
				});
			});
	}

	protected function setUsersTimezone(User $user): void
	{
		date_default_timezone_set($user->setting->timezone ?? config('app.timezone'));
	}
}
