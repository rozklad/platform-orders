<?php namespace Sanatorium\Orders\Handlers\Payment;

use Sanatorium\Orders\Models\Payment;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface PaymentEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a payment is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a payment is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Payment  $payment
	 * @return mixed
	 */
	public function created(Payment $payment);

	/**
	 * When a payment is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Payment  $payment
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Payment $payment, array $data);

	/**
	 * When a payment is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Payment  $payment
	 * @return mixed
	 */
	public function updated(Payment $payment);

	/**
	 * When a payment is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Payment  $payment
	 * @return mixed
	 */
	public function deleted(Payment $payment);

}
