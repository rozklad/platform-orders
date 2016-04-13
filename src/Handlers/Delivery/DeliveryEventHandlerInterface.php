<?php namespace Sanatorium\Orders\Handlers\Delivery;

use Sanatorium\Orders\Models\Delivery;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface DeliveryEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a delivery is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a delivery is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Delivery  $delivery
	 * @return mixed
	 */
	public function created(Delivery $delivery);

	/**
	 * When a delivery is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Delivery  $delivery
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Delivery $delivery, array $data);

	/**
	 * When a delivery is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Delivery  $delivery
	 * @return mixed
	 */
	public function updated(Delivery $delivery);

	/**
	 * When a delivery is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Delivery  $delivery
	 * @return mixed
	 */
	public function deleted(Delivery $delivery);

}
