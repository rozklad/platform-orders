<?php namespace Sanatorium\Orders\Handlers\Customer;

use Illuminate\Events\Dispatcher;
use Sanatorium\Orders\Models\Customer;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class CustomerEventHandler extends BaseEventHandler implements CustomerEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.orders.customer.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.orders.customer.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.orders.customer.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.orders.customer.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.orders.customer.deleted', __CLASS__.'@deleted');
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
	public function created(Customer $customer)
	{
		$this->flushCache($customer);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Customer $customer, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Customer $customer)
	{
		$this->flushCache($customer);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Customer $customer)
	{
		$this->flushCache($customer);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Orders\Models\Customer  $customer
	 * @return void
	 */
	protected function flushCache(Customer $customer)
	{
		$this->app['cache']->forget('sanatorium.orders.customer.all');

		$this->app['cache']->forget('sanatorium.orders.customer.'.$customer->id);
	}

}
