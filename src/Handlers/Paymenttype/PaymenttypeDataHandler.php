<?php namespace Sanatorium\Orders\Handlers\Paymenttype;

class PaymenttypeDataHandler implements PaymenttypeDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
