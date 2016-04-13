<?php namespace Sanatorium\Orders\Handlers\Orderstatus;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Orderstatus;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class OrderstatusEventHandler extends BaseEventHandler implements OrderstatusEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.orderstatus.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.orderstatus.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.orderstatus.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.orderstatus.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.orderstatus.deleted', __CLASS__.'@deleted');
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
	public function created(Orderstatus $orderstatus)
	{
		$this->flushCache($orderstatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Orderstatus $orderstatus, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Orderstatus $orderstatus)
	{
		$this->flushCache($orderstatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Orderstatus $orderstatus)
	{
		$this->flushCache($orderstatus);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Orderstatus  $orderstatus
	 * @return void
	 */
	protected function flushCache(Orderstatus $orderstatus)
	{
		$this->app['cache']->forget('sanatorium.orders.orderstatus.all');

		$this->app['cache']->forget('sanatorium.orders.orderstatus.'.$orderstatus->id);
	}

}
