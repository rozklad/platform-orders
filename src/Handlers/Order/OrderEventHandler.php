<?php namespace Sanatorium\Orders\Handlers\Order;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Order;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class OrderEventHandler extends BaseEventHandler implements OrderEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.order.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.order.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.order.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.order.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.order.deleted', __CLASS__.'@deleted');
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
	public function created(Order $order)
	{
		$this->flushCache($order);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Order $order, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Order $order)
	{
		$this->flushCache($order);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Order $order)
	{
		$this->flushCache($order);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Order  $order
	 * @return void
	 */
	protected function flushCache(Order $order)
	{
		$this->app['cache']->forget('sanatorium.orders.order.all');

		$this->app['cache']->forget('sanatorium.orders.order.'.$order->id);
	}

}
