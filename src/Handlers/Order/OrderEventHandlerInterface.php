<?php namespace Sanatorium\Orders\Handlers\Order;

use Sanatorium\Orders\Models\Order;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface OrderEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a order is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a order is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Order  $order
	 * @return mixed
	 */
	public function created(Order $order);

	/**
	 * When a order is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Order  $order
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Order $order, array $data);

	/**
	 * When a order is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Order  $order
	 * @return mixed
	 */
	public function updated(Order $order);

	/**
	 * When a order is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Order  $order
	 * @return mixed
	 */
	public function deleted(Order $order);

}
