<?php namespace Sanatorium\Orders\Handlers\Customer;

class CustomerDataHandler implements CustomerDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
