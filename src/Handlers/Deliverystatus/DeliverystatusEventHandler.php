<?php namespace Sanatorium\Orders\Handlers\Deliverystatus;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Deliverystatus;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class DeliverystatusEventHandler extends BaseEventHandler implements DeliverystatusEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.deliverystatus.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.deliverystatus.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.deliverystatus.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.deliverystatus.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.deliverystatus.deleted', __CLASS__.'@deleted');
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
	public function created(Deliverystatus $deliverystatus)
	{
		$this->flushCache($deliverystatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Deliverystatus $deliverystatus, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Deliverystatus $deliverystatus)
	{
		$this->flushCache($deliverystatus);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Deliverystatus $deliverystatus)
	{
		$this->flushCache($deliverystatus);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverystatus  $deliverystatus
	 * @return void
	 */
	protected function flushCache(Deliverystatus $deliverystatus)
	{
		$this->app['cache']->forget('sanatorium.orders.deliverystatus.all');

		$this->app['cache']->forget('sanatorium.orders.deliverystatus.'.$deliverystatus->id);
	}

}
