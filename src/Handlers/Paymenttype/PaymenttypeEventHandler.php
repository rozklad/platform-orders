<?php namespace Sanatorium\Orders\Handlers\Paymenttype;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Paymenttype;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class PaymenttypeEventHandler extends BaseEventHandler implements PaymenttypeEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.paymenttype.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.paymenttype.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.paymenttype.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.paymenttype.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.paymenttype.deleted', __CLASS__.'@deleted');
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
	public function created(Paymenttype $paymenttype)
	{
		$this->flushCache($paymenttype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Paymenttype $paymenttype, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Paymenttype $paymenttype)
	{
		$this->flushCache($paymenttype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Paymenttype $paymenttype)
	{
		$this->flushCache($paymenttype);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Paymenttype  $paymenttype
	 * @return void
	 */
	protected function flushCache(Paymenttype $paymenttype)
	{
		$this->app['cache']->forget('sanatorium.orders.paymenttype.all');

		$this->app['cache']->forget('sanatorium.orders.paymenttype.'.$paymenttype->id);
	}

}
