<?php

return [

	// General messages
	'not_found' => 'Order [:id] does not exist.',

	// Success messages
	'success' => [
		'create' => 'Order was successfully created.',
		'update' => 'Order was successfully updated.',
		'delete' => 'Order was successfully deleted.',
		'reverse'	=> 'Payment was succesfully reverted.',
		'refund'	=> 'Payment was succesfully refunded.',
		'close'		=> 'Payment was succesfully closed.',
	],

	// Error messages
	'error' => [
		'create' => 'There was an issue creating the order. Please try again.',
		'update' => 'There was an issue updating the order. Please try again.',
		'delete' => 'There was an issue deleting the order. Please try again.',
		'reverse'	=> 'There was an issue reverting payment for the order. Please try again.',
		'refund'	=> 'There was an issue refunding payment for the order. Please try again.',
		'close'		=> 'There was an issue closing payment for the order. Please try again.',
	],

];
