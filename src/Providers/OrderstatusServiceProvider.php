<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class OrderstatusServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Orderstatus']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.orderstatus.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.orderstatus', 'Sanatorium\Orders\Repositories\Orderstatus\OrderstatusRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.orderstatus.handler.data', 'Sanatorium\Orders\Handlers\Orderstatus\OrderstatusDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.orderstatus.handler.event', 'Sanatorium\Orders\Handlers\Orderstatus\OrderstatusEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.orderstatus.validator', 'Sanatorium\Orders\Validator\Orderstatus\OrderstatusValidator');
	}

}
