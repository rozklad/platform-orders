<?php namespace Sanatorium\Orders\Handlers\Paymentstatus;

use Sanatorium\Orders\Models\Paymentstatus;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface PaymentstatusEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a paymentstatus is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a paymentstatus is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymentstatus  $paymentstatus
	 * @return mixed
	 */
	public function created(Paymentstatus $paymentstatus);

	/**
	 * When a paymentstatus is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymentstatus  $paymentstatus
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Paymentstatus $paymentstatus, array $data);

	/**
	 * When a paymentstatus is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymentstatus  $paymentstatus
	 * @return mixed
	 */
	public function updated(Paymentstatus $paymentstatus);

	/**
	 * When a paymentstatus is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymentstatus  $paymentstatus
	 * @return mixed
	 */
	public function deleted(Paymentstatus $paymentstatus);

}
