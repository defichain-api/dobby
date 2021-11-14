<?php

return [
	'base_uri' => env('DEFICHAIN_API_BASE_URI', 'https://next.defichain-api.io/v1/'),

	'vaults'      => [
		'id_or_address' => 'vaults/id/%s',
		'multiple'      => 'vaults/addresses',
	],
	'loan_scheme' => [
		'get' => 'loan_schemes',
	],
];
