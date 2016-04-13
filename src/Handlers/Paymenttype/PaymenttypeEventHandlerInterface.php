<?php namespace Sanatorium\Orders\Handlers\Paymenttype;

use Sanatorium\Orders\Models\Paymenttype;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface PaymenttypeEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a paymenttype is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a paymenttype is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymenttype  $paymenttype
	 * @return mixed
	 */
	public function created(Paymenttype $paymenttype);

	/**
	 * When a paymenttype is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymenttype  $paymenttype
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Paymenttype $paymenttype, array $data);

	/**
	 * When a paymenttype is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymenttype  $paymenttype
	 * @return mixed
	 */
	public function updated(Paymenttype $paymenttype);

	/**
	 * When a paymenttype is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymenttype  $paymenttype
	 * @return mixed
	 */
	public function deleted(Paymenttype $paymenttype);

}
