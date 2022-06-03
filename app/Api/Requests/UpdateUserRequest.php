<?php

namespace App\Api\Requests;

use App\Enum\CardVisualization;
use App\Enum\SummaryInterval;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateUserRequest extends ApiRequest
{
	#[ArrayShape([
		'language'                              => "array",
		'uiTheme'                               => "array",
		'depositFromAddress'                    => "array",
		'depositInfoMail'                       => "array",
		'summaryInterval'                       => "array",
		'currentRatioEnabled'                   => "string[]",
		'uiPrivacyEnabled'                      => "string[]",
		'uiDashboardHealthSummaryEnabled'       => "string[]",
		'uiDashboardCollateralInfoEnabled'      => "string[]",
		'uiDashboardCollateralWaypointsEnabled' => "string[]",
		'uiDashboardCardsAsCarousel'            => "string[]",
		'timezone'                              => "array",
	])]
	public function rules(): array
	{
		return [
			'language'                              => [
				'sometimes',
				'string',
				Rule::in(config('app.available_locales')),
			],
			'uiTheme'                               => [
				'sometimes',
				'string',
				Rule::in(config('app.available_themes')),
			],
			'depositFromAddress'                    => ['sometimes', 'alpha_num', 'min:34', 'max:42'],
			'depositInfoMail'                       => ['sometimes', 'email:rfc,dns'],
			'summaryInterval'                       => ['sometimes', 'string', Rule::in(SummaryInterval::ALL)],
			'currentRatioEnabled'                   => ['sometimes', 'boolean'],
			'uiPrivacyEnabled'                      => ['sometimes', 'boolean'],
			'uiDashboardHealthSummaryEnabled'       => ['sometimes', 'boolean'],
			'uiDashboardCollateralInfoEnabled'      => ['sometimes', 'boolean'],
			'uiDashboardCollateralWaypointsEnabled' => ['sometimes', 'boolean'],
			'uiDashboardCardsAsCarousel'            => ['sometimes', 'string', Rule::in(CardVisualization::ALL)],
			'timezone'                              => ['sometimes', 'string', Rule::in(array_keys(__('timezones')))],
		];
	}

	#[ArrayShape([
		'language.in'                   => "string",
		'uiTheme.in'                    => "string",
		'summaryInterval.in'            => "string",
		'timezone.in'                   => "string",
		'depositFromAddress.min'        => "string",
		'depositFromAddress.max'        => "string",
		'uiDashboardCardsAsCarousel.in' => "string",
		'timezone.in'                   => "string",
	])]
	public function messages(): array
	{
		return [
			'language.in'                   => sprintf('possible values are: %s',
				implode(', ', config('app.available_locales'))),
			'uiTheme.in'                    => sprintf('possible values are: %s',
				implode(', ', config('app.available_themes'))),
			'summaryInterval.in'            => sprintf('possible values are: %s', implode(', ', SummaryInterval::ALL)),
			'timezone.in'                   => sprintf('possible values are visible at: %s',
				route('web_app.list.timezones')),
			'depositFromAddress.min'        => 'Deposit address (defichain) must have 34 or 42 chars',
			'depositFromAddress.max'        => 'Deposit address (defichain) must have 34 or 42 chars',
			'uiDashboardCardsAsCarousel.in' => sprintf('possible values are: %s',
				implode(', ', CardVisualization::ALL)),
			'timezone.in'                   => sprintf('possible values are visible at: %s',
				route('web_app.list.timezones')),
		];
	}

	public function hasLanguage(): bool
	{
		return $this->has('language');
	}

	public function hasTheme(): bool
	{
		return $this->has('uiTheme');
	}

	public function hasDepositFromAddress(): bool
	{
		return $this->has('depositFromAddress');
	}

	public function hasDepositInfoMail(): bool
	{
		return $this->has('depositInfoMail');
	}

	public function hasSummaryInterval(): bool
	{
		return $this->has('summaryInterval');
	}

	public function hasCurrentRatioEnabled(): bool
	{
		return $this->has('currentRatioEnabled');
	}

	public function hasUiPrivacyEnabled(): bool
	{
		return $this->has('uiPrivacyEnabled');
	}

	public function hasUiDashboardHealthSummaryEnabled(): bool
	{
		return $this->has('uiDashboardHealthSummaryEnabled');
	}

	public function hasUiDashboardCollateralInfoEnabled(): bool
	{
		return $this->has('uiDashboardCollateralInfoEnabled');
	}

	public function hasUiDashboardCollateralWaypointsEnabled(): bool
	{
		return $this->has('uiDashboardCollateralWaypointsEnabled');
	}

	public function hasUiDashboardCardsAsCarousel(): bool
	{
		return $this->has('uiDashboardCardsAsCarousel');
	}

	public function hasTimezone(): bool
	{
		return $this->has('timezone');
	}

	public function language(): string
	{
		return $this->input('language');
	}

	public function theme(): string
	{
		return $this->input('uiTheme');
	}

	public function depositFromAddress(): string
	{
		return $this->input('depositFromAddress');
	}

	public function depositInfoMail(): string
	{
		return $this->input('depositInfoMail');
	}

	public function currentRatioEnabled(): string
	{
		return $this->input('currentRatioEnabled');
	}

	public function uiPrivacyEnabled(): bool
	{
		return $this->input('uiPrivacyEnabled');
	}

	public function uiDashboardHealthSummaryEnabled(): bool
	{
		return $this->input('uiDashboardHealthSummaryEnabled');
	}

	public function uiDashboardCollateralInfoEnabled(): bool
	{
		return $this->input('uiDashboardCollateralInfoEnabled');
	}

	public function uiDashboardCollateralWaypointsEnabled(): bool
	{
		return $this->input('uiDashboardCollateralWaypointsEnabled');
	}

	public function uiDashboardCardsAsCarousel(): string
	{
		return $this->input('uiDashboardCardsAsCarousel');
	}

	public function timezone(): string
	{
		return $this->input('timezone');
	}

	public function summaryInterval(): string
	{
		return $this->input('summaryInterval');
	}
}
