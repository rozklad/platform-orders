<?php namespace Sanatorium\Orders\Handlers\Delivery;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Delivery;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class DeliveryEventHandler extends BaseEventHandler implements DeliveryEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.delivery.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.delivery.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.delivery.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.delivery.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.delivery.deleted', __CLASS__.'@deleted');
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
	public function created(Delivery $delivery)
	{
		$this->flushCache($delivery);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Delivery $delivery, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Delivery $delivery)
	{
		$this->flushCache($delivery);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Delivery $delivery)
	{
		$this->flushCache($delivery);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Delivery  $delivery
	 * @return void
	 */
	protected function flushCache(Delivery $delivery)
	{
		$this->app['cache']->forget('sanatorium.orders.delivery.all');

		$this->app['cache']->forget('sanatorium.orders.delivery.'.$delivery->id);
	}

}
