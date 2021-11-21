<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PruneInactiveUsersCommand extends Command
{
	protected $signature = 'prune:inactive-users {--after=7}';
	protected $description = 'Prune inactive users. argument: after=X (in days)';

	public function handle(): void
	{
		$afterDays = $this->option('after');
		if ($afterDays < 7) {
			$this->warn('can\'t delete users created in the last week');

			return;
		}
		$users = User::withCount('vaults')
			->having('vaults_count', '=', 0)
			->where('created_at', '<', now()->subDays($afterDays))->get();

		$userCount = $users->count();
		if ($userCount === 0) {
			return;
		}

		$this->info(sprintf(
			'found %s users, unused for min %s days without vaults',
			$userCount,
			$afterDays
		));
		$users->each(function (User $user) {
			$user->notificationGateways()->delete();
			$this->info(sprintf('deleted notification gateways for user %s', $user->id()));
			if ($user->delete()) {
				$this->info(sprintf('deleted user %s', $user->id()));
			} else {
				$this->warn(sprintf('could not delete user %s', $user->id()));
			}
		});
	}
}
