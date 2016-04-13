<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Customer']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.customer.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.customer', 'Sanatorium\Orders\Repositories\Customer\CustomerRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.customer.handler.data', 'Sanatorium\Orders\Handlers\Customer\CustomerDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.customer.handler.event', 'Sanatorium\Orders\Handlers\Customer\CustomerEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.customer.validator', 'Sanatorium\Orders\Validator\Customer\CustomerValidator');
	}

}
