<?php namespace Sanatorium\Orders\Handlers\Payment;

class PaymentDataHandler implements PaymentDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
