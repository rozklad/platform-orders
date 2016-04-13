<?php

return [

	'title' 	=> 'Cart',
	'url' 		=> 'cart',
	'subtitle' 	=> 'Fill out the information below',

	'data' => [
		'total'    			=> 'Total',
		'subtotal' 			=> 'Subtotal',
		'delivery_price' 	=> 'Delivery',
	], 

	'messages' => [
		'empty' => 'There are no products in your cart'
	],

	'proceed' => [
		'checkout' 		=> 'Proceed to checkout',
		'delivery' 		=> 'Choose delivery',
		'user' 	   		=> 'Your details',
		'confirmation' 	=> 'Confirmation',
		'confirmed'     => 'Confirm'
	],

	'summary' => [
		'title' 			=> 'Summary',
		'name' 				=> 'Name',
		'quantity' 			=> 'Quantity',
		'price_single_vat' 	=> 'Price 1pc (with VAT)',
		'price_vat' 		=> 'Price (with VAT)',
	],

	'totals' => [
		'title' => 'Totals',
		'delivery' => 'Delivery',
	],

	'steps' => [
		'1'	=> 'Cart',
		//'2' => 'Choose delivery',
		//'3' => 'Your details',
		'3' => 'Choose delivery',
		'2' => 'Your details',
		'4' => 'Confirmation',
	],

	'personal' => [
		'email' => 'E-mail',
		'phone' => 'Phone',
	],

	'billing' => [
		'title' 	=> 'Billing address',
		'name' 		=> 'Name',
		'street' 	=> 'Street',
		'city' 		=> 'City',
		'zip' 		=> 'ZIP',
		'country' 	=> 'Country',
	],

	'delivery' => [
		'title' 	=> 'Delivery address',
		'name' 		=> 'Name',
		'street' 	=> 'Street',
		'city' 		=> 'City',
		'zip' 		=> 'ZIP',
		'country' 	=> 'Country',
	],

	'company' => [
		'title'		=> 'Company',
		'ic' 		=> 'VAT ID',
		'dic' 		=> 'VAT ID (international)',
	],

	'placed' => [
		'thank_you'     => 'Thank you for your order',
		'return'		=> 'Return to the home page',
		'messages'			=> [
			'payments'		=> [
				'success' 	=> 'Payment was successfully completed',
				'error'		=> 'Payment could not be finished',
			]
		]
	],

	'single' => [
		'title' => 'Order no.:id',
		'created_at' => 'Placed on :created_at'
	],

	'actions' => [
		'track' => 'Track',
		'clear' => 'Clear cart',
		'place' => 'Place',
		'continue' => 'Continue shopping',
	],

	'same_billing_as_delivery' => 'Ship to same address?',

	'alerts' => [
		'delivery_not_free' => 'Při objednávce nad :from je doprava zdarma.',
		'delivery_free' => 'Gratulujeme, Vaše objednávka přesahuje :from, doprava je zdarma.',
		'delivery_free_remains' => 'Zbývá ještě <strong>:remains</strong>, podívejte se na <a href="/">katalog</a>.',
	], 

	'free' => 'Free',

	'order_note' => 'Message for us',

	'optional' => 'optional',
];