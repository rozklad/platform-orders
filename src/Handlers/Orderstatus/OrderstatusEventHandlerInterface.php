<?php namespace Sanatorium\Orders\Handlers\Orderstatus;

use Sanatorium\Orders\Models\Orderstatus;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface OrderstatusEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a orderstatus is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a orderstatus is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Orderstatus  $orderstatus
	 * @return mixed
	 */
	public function created(Orderstatus $orderstatus);

	/**
	 * When a orderstatus is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Orderstatus  $orderstatus
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Orderstatus $orderstatus, array $data);

	/**
	 * When a orderstatus is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Orderstatus  $orderstatus
	 * @return mixed
	 */
	public function updated(Orderstatus $orderstatus);

	/**
	 * When a orderstatus is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Orderstatus  $orderstatus
	 * @return mixed
	 */
	public function deleted(Orderstatus $orderstatus);

}
