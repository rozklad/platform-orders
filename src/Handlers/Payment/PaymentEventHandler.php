<?php namespace Sanatorium\Orders\Handlers\Payment;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Payment;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class PaymentEventHandler extends BaseEventHandler implements PaymentEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.payment.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.payment.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.payment.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.payment.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.payment.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Payment $payment)
	{
		$this->flushCache($payment);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Payment $payment, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Payment $payment)
	{
		$this->flushCache($payment);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Payment $payment)
	{
		$this->flushCache($payment);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Payment  $payment
	 * @return void
	 */
	protected function flushCache(Payment $payment)
	{
		$this->app['cache']->forget('sanatorium.orders.payment.all');

		$this->app['cache']->forget('sanatorium.orders.payment.'.$payment->id);
	}

}
