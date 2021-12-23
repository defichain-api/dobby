<?php

return [
	'base_uri' => 'https://ocean.defichain.com/v0/mainnet/',

	'vaults'       => [
		'id' => 'loans/vaults/%s',
		'get'           => 'loans/vaults?size=30',
	],
	'loan_schemes' => [
		'get' => 'loans/schemes',
	],

	'fixed_interval_prices' => [
		'get' => 'prices',
	],
	'stats' => [
		'get' => 'stats',
	],
];
