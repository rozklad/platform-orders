<?php namespace Sanatorium\Orders\Handlers\Paymentstatus;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Paymentstatus;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class PaymentstatusEventHandler extends BaseEventHandler implements PaymentstatusEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.paymentstatus.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.paymentstatus.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.paymentstatus.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.paymentstatus.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.paymentstatus.deleted', __CLASS__.'@deleted');
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
	public function created(Paymentstatus $paymentstatus)
	{
		$this->flushCache($paymentstatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Paymentstatus $paymentstatus, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Paymentstatus $paymentstatus)
	{
		$this->flushCache($paymentstatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Paymentstatus $paymentstatus)
	{
		$this->flushCache($paymentstatus);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymentstatus  $paymentstatus
	 * @return void
	 */
	protected function flushCache(Paymentstatus $paymentstatus)
	{
		$this->app['cache']->forget('sanatorium.orders.paymentstatus.all');

		$this->app['cache']->forget('sanatorium.orders.paymentstatus.'.$paymentstatus->id);
	}

}
