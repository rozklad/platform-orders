<?php namespace Sanatorium\Orders\Handlers\Deliverytype;

use Sanatorium\Orders\Models\Deliverytype;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface DeliverytypeEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a deliverytype is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a deliverytype is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverytype  $deliverytype
	 * @return mixed
	 */
	public function created(Deliverytype $deliverytype);

	/**
	 * When a deliverytype is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverytype  $deliverytype
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Deliverytype $deliverytype, array $data);

	/**
	 * When a deliverytype is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverytype  $deliverytype
	 * @return mixed
	 */
	public function updated(Deliverytype $deliverytype);

	/**
	 * When a deliverytype is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverytype  $deliverytype
	 * @return mixed
	 */
	public function deleted(Deliverytype $deliverytype);

}
