<?php

return [

	'title' => 'Cart',

	'cart_mode' => [
		'label' => 'Cart mode',
		'info' => 'Select cart mode',

		'values' => [
			'single' => 'Single page cart',
			'steps' => 'Multiple steps cart'
		]
	],

	'async' => [
		'label' => 'Async',
		'info' => 'Asynchronous cart',

		'values' => [
			'true' => 'Yes',
			'false' => 'No'
		]
	],

	'ff_delivery_payment' => [
		'label' => 'Fast forward delivery & payment',
		'info' => 'Fast forward delivery & payment (preselected)',

		'values' => [
			'true' => 'Yes',
			'false' => 'No'
		]
	],

	'show_slips' => [
		'label' => 'Show order slips',
		'info' => 'Show order slips',

		'values' => [
			'true' => 'Yes',
			'false' => 'No'
		]
	],

	'guess_country' => [
		'label' => 'Guess country',
		'info' => 'Guess country in order process, if not specified otherwise by predefined address',

		'values' => [
			'true' => 'Yes',
			'false' => 'No'
		]
	],

	'only_logged_in' => [
		'label' => 'Only for logged in',
		'info' => 'Only for logged in users',

		'values' => [
			'true' => 'Yes',
			'false' => 'No'
		]
	],

	'netimpact_key' => [
		'label' => 'NetImpact.com key',
		'info' => 'API key to query the users location, default limit of free API is 250 queries per day',
	],

];