<?php namespace Sanatorium\Orders\Validator\Order;

interface OrderValidatorInterface {

	/**
	 * Updating a order scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
