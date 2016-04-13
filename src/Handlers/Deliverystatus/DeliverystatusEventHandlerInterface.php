<?php namespace Sanatorium\Orders\Handlers\Deliverystatus;

use Sanatorium\Orders\Models\Deliverystatus;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface DeliverystatusEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a deliverystatus is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a deliverystatus is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverystatus  $deliverystatus
	 * @return mixed
	 */
	public function created(Deliverystatus $deliverystatus);

	/**
	 * When a deliverystatus is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverystatus  $deliverystatus
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Deliverystatus $deliverystatus, array $data);

	/**
	 * When a deliverystatus is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverystatus  $deliverystatus
	 * @return mixed
	 */
	public function updated(Deliverystatus $deliverystatus);

	/**
	 * When a deliverystatus is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverystatus  $deliverystatus
	 * @return mixed
	 */
	public function deleted(Deliverystatus $deliverystatus);

}
