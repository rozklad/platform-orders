<?php

return [

	'title'  => 'Commandes',

	'tabs' => [

		'general'    	=> 'Commande',
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

		'back' => 'Retours à vos commandes',

		'reverse' => 'Reverse payment',
		'refund' => 'Partial refund',
		'close' => 'Close payment',

		'clear_cache' => 'Clear cache',
	],

	'attributes' => [

		'public_id' => 'Commande',
		'created_at' => 'Date',
		'price' => 'Prix',
		'status' => 'Status',

	],

	'tracking' => [
		'placed' => 'Commande effectuée',
		'status' => 'La commande a été :status',
	],
];
