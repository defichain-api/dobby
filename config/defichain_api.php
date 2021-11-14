<?php

return [
	'base_uri' => env('DEFICHAIN_API_BASE_URI', 'https://next.defichain-api.io/v1/'),

	'vaults'      => [
		'id_or_address' => 'vaults/id/%s',
		'multiple'      => 'vaults/addresses',
	],
	'loan_schemes' => [
		'get' => 'loan_schemes',
	],

	'fixed_interval_prices' => [
		'get' => 'fixed_price_intervals',
	],
];
