<?php

return [

	'title' => 'Košík',

	'cart_mode' => [
		'label' => 'Typ košíku',
		'info' => 'Vyberte typ košíku',

		'values' => [
			'single' => 'Jednostránkový košík',
			'steps' => 'Vícestránkový košík'
		]
	],

	'async' => [
		'label' => 'Asynchronní košík',
		'info' => 'Asynchronní košík',

		'values' => [
			'true' => 'Ano',
			'false' => 'Ne'
		]
	],

	'ff_delivery_payment' => [
		'label' => 'Přeskočit dopravu a platbu',
		'info' => 'Přeskočit dopravu a platbu (předvoleno)',

		'values' => [
			'true' => 'Ano',
			'false' => 'Ne'
		]
	],

	'show_slips' => [
		'label' => 'Zobrazit dodací listy',
		'info' => 'Zobrazit dodací listy (předvoleno)',

		'values' => [
			'true' => 'Ano',
			'false' => 'Ne'
		]
	],

	'only_logged_in' => [
		'label' => 'Pouze pro registrované',
		'info' => 'Pouze pro registrované',

		'values' => [
			'true' => 'Ano',
			'false' => 'Ne'
		]
	],

];