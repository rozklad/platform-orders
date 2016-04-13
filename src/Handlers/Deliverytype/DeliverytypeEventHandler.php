<?php namespace Sanatorium\Orders\Handlers\Deliverytype;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Deliverytype;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class DeliverytypeEventHandler extends BaseEventHandler implements DeliverytypeEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.deliverytype.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.deliverytype.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.deliverytype.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.deliverytype.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.deliverytype.deleted', __CLASS__.'@deleted');
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
	public function created(Deliverytype $deliverytype)
	{
		$this->flushCache($deliverytype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Deliverytype $deliverytype, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Deliverytype $deliverytype)
	{
		$this->flushCache($deliverytype);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Deliverytype $deliverytype)
	{
		$this->flushCache($deliverytype);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Deliverytype  $deliverytype
	 * @return void
	 */
	protected function flushCache(Deliverytype $deliverytype)
	{
		$this->app['cache']->forget('sanatorium.orders.deliverytype.all');

		$this->app['cache']->forget('sanatorium.orders.deliverytype.'.$deliverytype->id);
	}

}
