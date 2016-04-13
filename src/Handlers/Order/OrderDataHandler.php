<?php namespace Sanatorium\Orders\Handlers\Order;

class OrderDataHandler implements OrderDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
