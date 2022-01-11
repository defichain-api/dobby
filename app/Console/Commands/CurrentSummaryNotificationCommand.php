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
		User::withCount('gateways')
			->having('gateways_count', '>', 0)
			->whereHas('setting', function ($query) {
				match (now()->hour) {
					8 => $query->whereIn('summary_interval', [
						SummaryInterval::DAILY_ONCE,
						SummaryInterval::DAILY_TWICE,
						SummaryInterval::DAILY_THRICE,
						SummaryInterval::HOURLY,
					]),
					13 => $query->whereIn('summary_interval', [
						SummaryInterval::DAILY_THRICE,
						SummaryInterval::HOURLY,
					]),
					20 => $query->whereIn('summary_interval', [
						SummaryInterval::DAILY_TWICE,
						SummaryInterval::DAILY_THRICE,
						SummaryInterval::HOURLY,
					]),
					default => $query->where('summary_interval', SummaryInterval::HOURLY)
				};
			})
			->chunk(100, function ($users) {
				$users->each(function (User $user) {
					if (is_null($user->vaults->first())) {
						return;
					}
					$user->notify(new CurrentSummaryTriggerNotification($user->vaults->first()));
				});
			});
	}
}
