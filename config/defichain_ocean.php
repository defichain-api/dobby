<?php

return [
	'base_uri'              => env('OCEAN_BASE_URL', 'https://ocean.defichain.com/v0/mainnet/'),
	'base_uri_fallback'     => env('OCEAN_BASE_URL_FALLBACK', 'https://ocean.mydefichain.com/v0/mainnet/'),
	'vaults'                => [
		'id'        => 'loans/vaults/%s',
		'get'       => 'loans/vaults?size=30',
		'dusd_loan' => 'loans/tokens/15',
	],
	'loan_schemes'          => [
		'get' => 'loans/schemes',
	],
	'fixed_interval_prices' => [
		'get' => 'prices',
	],
	'stats'                 => [
		'get' => 'stats',
	],
];
