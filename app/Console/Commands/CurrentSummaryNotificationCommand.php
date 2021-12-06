<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\CurrentSummaryTriggerNotification;
use Illuminate\Console\Command;

class CurrentSummaryNotificationCommand extends Command
{
	protected $signature = 'notification:current-summary';
	protected $description = 'Send a daily summary to all users';

	public function handle()
	{
		User::withCount('gateways')
			->having('gateways_count', '>', 0)
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
