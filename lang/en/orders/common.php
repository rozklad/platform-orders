<?php

return [

	'title'  => 'Orders',

	'tabs' => [

		'general'    	=> 'Order',
		'attributes' 	=> 'Attributes',
		'debug'			=> 'Debug',

	],

	'actions' => [

		'default_email' => [
			'send' => 'Send "order received" mail',
			'success' => '"Order received" mail succesfully sent'
		],

		'status_change' => [
			'success' => 'Order status was succesfully changed',
		],

		'customer' => [
			'show' => 'Show customer',
			'repair' => 'Find customers',
		],

		'back' => 'Back to your orders',

		'reverse' => 'Reverse payment',
		'refund' => 'Partial refund',
		'close' => 'Close payment',

		'clear_cache' => 'Clear cache',

		'slips' => 'Back to your slips',
	],

	'attributes' => [

		'public_id' => 'Order',
		'created_at' => 'Date',
		'price' => 'Price',
		'status' => 'Status',

	],

	'tracking' => [
		'placed' => 'Order was placed',
		'status' => 'Order was :status',
	],
];
