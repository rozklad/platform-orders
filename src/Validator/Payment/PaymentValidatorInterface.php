<?php namespace Sanatorium\Orders\Validator\Payment;

interface PaymentValidatorInterface {

	/**
	 * Updating a payment scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
