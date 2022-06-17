<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Statistic */
class StatisticResource extends JsonResource
{
	/**
	 * @param Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'date'           => $this->date,
			'user_count'     => $this->user_count,
			'vault_count'    => $this->vault_count,
			'messages'       => [
				'sum_messages' => $this->sum_telegram_messages + $this->sum_mail_messages +
					$this->sum_webhook_messages + $this->sum_phone_messages,
				'types'        => [
					'trigger_warnings' => $this->sum_trigger_notifications,
					'summary'          => $this->sum_daily_messages,
					'may_liquidate'    => $this->sum_may_liquidate_notifications,
					'in_liquidation'   => $this->sum_in_liquidation_notifications,
				],
				'gateways'     => [
					'telegram' => $this->sum_telegram_messages,
					'mail'     => $this->sum_mail_messages,
					'webhook'  => $this->sum_webhook_messages,
					'phone'    => $this->sum_phone_messages,
				],
			],
			'sum_collateral' => $this->sum_collateral,
			'sum_loan'       => $this->sum_loan,
			'avg_ratio'      => $this->avg_ratio,
			'median_ratio'   => $this->median_ratio,
		];
	}
}
