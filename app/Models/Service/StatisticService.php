<?php

namespace App\Models\Service;

use App\Enum\NotificationGatewayType;
use App\Enum\NotificationTriggerType;
use App\Models\Statistic;
use App\Models\User;
use App\Models\Vault;
use DB;

class StatisticService
{
	public function updateUserCount(): self
	{
		Statistic::updateOrCreate([
			'date' => today(),
		], [
			'user_count' => User::count(),
		]);

		return $this;
	}

	public function updateVaultCount(): self
	{
		Statistic::updateOrCreate([
			'date' => today(),
		], [
			'vault_count' => Vault::count(),
		]);

		return $this;
	}

	public function updateCollateralSum(): self
	{
		Statistic::updateOrCreate([
			'date' => today(),
		], [
			'sum_collateral' => Vault::where('collateralValue', '>', 0)->sum('collateralValue'),
		]);

		return $this;
	}

	public function updateLoanSum(): self
	{
		Statistic::updateOrCreate([
			'date' => today(),
		], [
			'sum_loan' => Vault::where('loanValue', '>', 0)->sum('loanValue'),
		]);

		return $this;
	}

	public function updateAvgRatio(): self
	{
		Statistic::updateOrCreate([
			'date' => today(),
		], [
			'avg_ratio' => Vault::where('collateralRatio', '>', 0)->avg('collateralRatio'),
		]);

		return $this;
	}

	public function messageTriggerUsed(string $type): self
	{
		$type = match ($type) {
			NotificationTriggerType::DAILY => 'sum_daily_messages',
			NotificationTriggerType::INFO => 'sum_info_notifications',
			NotificationTriggerType::WARNING => 'sum_warning_notifications',
			NotificationTriggerType::MAY_LIQUIDATION => 'sum_may_liquidate_notifications',
			NotificationTriggerType::IN_LIQUIDATION => 'sum_in_liquidation_notifications',
		};
		if (!isset($type)) {
			return $this;
		}

		Statistic::updateOrCreate([
			'date' => today(),
		], [
			$type => DB::raw(sprintf('%s + 1', $type)),
		]);

		return $this;
	}

	public function messageGatewayUsed(string $gateway): self
	{
		$selectedGateway = match ($gateway) {
			NotificationGatewayType::TELEGRAM => 'sum_telegram_messages',
			NotificationGatewayType::MAIL => 'sum_mail_messages',
			NotificationGatewayType::WEBHOOK => 'sum_webhook_messages',
		};
		if (!isset($selectedGateway)) {
			return $this;
		}

		Statistic::updateOrCreate([
			'date' => today(),
		], [
			$selectedGateway => DB::raw(sprintf('%s + 1', $selectedGateway)),
		]);

		return $this;
	}
}
