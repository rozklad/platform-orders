<?php

return [

	'title'  => 'Přijaté objednávky',

	'tabs' => [

		'general'    	=> 'Objednávka',
		'attributes' 	=> 'Atributy',
		'debug' 		=> 'Debug',

	],

	'actions' => [

		'default_email' => [
			'send' => 'Odeslat e-mail "vaše objednávka byla přijata"',
			'success' => 'E-mail "vaše objednávka byla přijata" byl úspěšně odeslán'
		],
		
		'status_change' => [
			'success' => 'Stav objednávky byl úspěšně změněn',
		],

		'customer' => [
			'show' => 'Zobrazit zákazníka',
			'repair' => 'Najít zákazníky',
		],

		'back' => 'Zpět k Vašim objednávkám',

		'reverse' => 'Vrátit platbu',
		'refund' => 'Vrátit část',
		'close' => 'Uzavřít platbu',

		'clear_cache' => 'Vymazat cache',

		'slips' => 'Zpět k Vašim dodacím listům',
	],

	'attributes' => [

		'public_id' => 'Objednávka',
		'created_at' => 'Datum',
		'price' => 'Cena s DPH',
		'status' => 'Stav',

	],

	'single' => [

		'title' => 'Objednávka #:id',
		'created_at' => 'Uskutečněna :created_at'
	
	],

	'tracking' => [
		'placed' => 'Objednávka byla uskutečněna',
		'status' => 'Objednávka byla :status',
	],
];
