<?php

namespace App\Console\Commands;

use App\Models\EnabledBetaFeature;
use App\Models\User;
use Illuminate\Console\Command;

class BetaFeatureCommand extends Command
{
	protected $signature = 'betafeature';
	protected $description = 'Add/remove beta feature for user';
	protected ?User $user;

	public function handle()
	{
		$this->user = User::find($this->ask('Users Dobby key'));
		$action = $this->choice('Add or remove beta feature for user?', ['add', 'remove']);

		if (is_null($this->user)) {
			$this->error('Dobby key not found');

			return;
		}

		if ($action == 'add') {
			$this->addBetaFeature();
		} elseif ($action == 'remove') {
			$this->removeBetaFeature();
		}
	}

	protected function addBetaFeature()
	{
		EnabledBetaFeature::create([
			'userId'  => $this->user->id,
			'feature' => $this->ask('Feature name'),
		]);
		$this->info('Beta Feature set for user');
	}

	protected function removeBetaFeature()
	{
		$currentActiveFeatures = $this->user->enabledBetaFeatures;
		$currentFeaturesArray = $currentActiveFeatures->pluck('feature')->toArray();
		$currentFeaturesArray[] = 'cancel';
		$choice = $this->choice('Which one do you want to remove?', $currentFeaturesArray);

		if ($choice == 'cancel') {
			$this->info('Cancelling. No data changed');

			return;
		}

		$currentActiveFeatures->where('feature', $choice)->first()->delete();
		$this->info('removed beta feature');
	}
}
