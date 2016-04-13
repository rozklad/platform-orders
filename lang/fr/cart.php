<?php

return [

	'title' 	=> 'Mon sac',
	'url' 		=> 'cart',
	'subtitle' 	=> 'Fill out the information below',

	'data' => [
		'total'    			=> 'Total',
		'subtotal' 			=> 'Subtotal',
		'delivery_price' 	=> 'Delivery',
	], 

	'messages' => [
		'empty' => 'Il n’y a pas de produit dans votre sac'
	],

	'proceed' => [
		'checkout' 		=> 'Passer à la caisse',
		'delivery' 		=> 'Choisir la livraison',
		'user' 	   		=> 'vos Informations',
		'confirmation' 	=> 'Confirmation',
		'confirmed'     => 'Confirm'
	],

	'summary' => [
		'title' 			=> 'Sommaire',
		'name' 				=> 'Name',
		'quantity' 			=> 'Quantité',
		'price_single_vat' 	=> 'Prix 1pc (Avec TVA)',
		'price_vat' 		=> 'Prix (Avec TVA)',
	],

	'totals' => [
		'title' => 'Totals',
		'delivery' => 'Delivery',
	],

	'steps' => [
		'1'	=> 'Mon sac',
		'2' => 'Choisir la livraison',
		'3' => 'vos Informations',
		'4' => 'Confirmation',
	],

	'personal' => [
		'email' => 'Email',
	],

	'billing' => [
		'title' 	=> 'Adresse de facturation',
		'name' 		=> 'Nom',
		'street' 	=> 'Rue',
		'city' 		=> 'Ville',
		'zip' 		=> 'Code Postal',
		'country' 	=> 'Pays',
	],

	'delivery' => [
		'title' 	=> 'Adresse de livraison',
		'name' 		=> 'Nom',
		'street' 	=> 'Rue',
		'city' 		=> 'Ville',
		'zip' 		=> 'Code Postal',
		'country' 	=> 'Pays',
	],

	'company' => [
		'title'		=> 'Compagnies',
		'ic' 		=> 'TVA ID',
		'dic' 		=> 'TVA ID (international)',
	],

	'placed' => [
		'thank_you'     => 'Merci pour votre achat',
		'return'		=> 'Returnez a la page principale',
		'messages'			=> [
			'payments'		=> [
				'success' 	=> 'Paiement effectué avec succès',
				'error'		=> 'Le paiement n\'a pas pu être terminé',
			]
		]
	],

	'single' => [
		'title' => 'Commandé no.:id',
		'created_at' => 'Commandé le :created_at'
	],

	'actions' => [
		'track' => 'Suivre',
		'clear' => 'Vider mon sac',
		'place' => 'Place',
		'continue' => 'Continue shopping',
	],

	'same_billing_as_delivery' => 'Livrer à la meme adresse?',
];