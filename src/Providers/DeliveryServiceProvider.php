<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class DeliveryServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Delivery']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.delivery.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.delivery', 'Sanatorium\Orders\Repositories\Delivery\DeliveryRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.delivery.handler.data', 'Sanatorium\Orders\Handlers\Delivery\DeliveryDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.delivery.handler.event', 'Sanatorium\Orders\Handlers\Delivery\DeliveryEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.delivery.validator', 'Sanatorium\Orders\Validator\Delivery\DeliveryValidator');
	}

}
