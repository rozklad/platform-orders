<?php

return [

	'title' 	=> 'Košík',
	'url' 		=> 'kosik',
	'subtitle' 	=> 'Zbývá už jen několik kroků',

	'data' => [
		'total'    			=> 'Celkem',
		'subtotal' 			=> 'Celkem zboží',
		'delivery_price' 	=> 'Cena za dopravu',
	], 

	'actions' => [
		'clear' => 'Vysypat košík',
		'place' => 'Objednat',
		'continue' => 'Pokračovat v nákupu',
		'track' => 'Sledovat',
	],

	'messages' => [
		'empty' => 'Ve vašem košíku nejsou žádné produkty'
	],

	'proceed' => [
		'checkout' 		=> 'Přejít k objednávce',
		'delivery' 		=> 'Zvolit doručení',
		'user' 	   		=> 'Zvolit dodací adresu',
		'confirmation' 	=> 'Shrnutí',
		'confirmed'     => 'Potvrdit'
	],

	'summary' => [
		'title' 			=> 'Shrnutí',
		'name' 				=> 'Název',
		'quantity' 			=> 'Množství',
		'price_single_vat' 	=> 'Cena KS (s DPH)',
		'price_vat' 		=> 'Cena (s DPH)',
	],

	'totals' => [
		'title' => 'Celkem',
		'delivery' => 'Doprava',
	],

	'steps' => [
		'1'	=> 'Košík',
		'2' => 'Vyberte dopravu',
		'3' => 'Vaše údaje',
		'4' => 'Shrnutí',
	],

	'billing' => [
		'title' 		=> 'Fakturační údaje',
		'name' 			=> 'Jméno a příjmení',
		'street' 		=> 'Ulice',
		'city' 			=> 'Město',
		'zip' 			=> 'PSČ',
		'country' 		=> 'Země',
	],

	'delivery' => [
		'title' 		=> 'Dodací údaje',
		'name' 			=> 'Jméno a příjmení',
		'street' 		=> 'Ulice',
		'city' 			=> 'Město',
		'zip' 			=> 'PSČ',
		'country' 		=> 'Země',
	],

	'company' => [
		'title' 		=> 'Firemní údaje',
		'ic' 			=> 'IČ',
		'dic' 			=> 'DIČ',
	],

	'single' => [
		'title' => 'Objednávka číslo :id',
		'created_at' => 'Uskutečněna :created_at'
	],

	'placed' => [
		'thank_you'     => 'Děkujeme za Vaši objednávku',
		'return'		=> 'Vraťte se na hlavní stránku',
		'messages'			=> [
			'payments'		=> [
				'success' 	=> 'Platba byla úspěšně dokončena',
				'error'		=> 'Platbu nebylo možné úspěšně dokončit',
			]
		]
	],

	'same_billing_as_delivery' => 'Doručit na stejnou adresu?',

	'alerts' => [
		'delivery_not_free' => 'Při objednávce nad :from je doprava zdarma.',
		'delivery_free' => 'Gratulujeme, Vaše objednávka přesahuje :from, doprava je zdarma.',
		'delivery_free_remains' => 'Zbývá ještě <strong>:remains</strong>, podívejte se na <a href="/">katalog</a>.',
	], 

	'free' => 'Zdarma',
];