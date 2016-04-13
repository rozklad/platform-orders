<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class DeliverytypeServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Deliverytype']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.deliverytype.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.deliverytype', 'Sanatorium\Orders\Repositories\Deliverytype\DeliverytypeRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.deliverytype.handler.data', 'Sanatorium\Orders\Handlers\Deliverytype\DeliverytypeDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.deliverytype.handler.event', 'Sanatorium\Orders\Handlers\Deliverytype\DeliverytypeEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.deliverytype.validator', 'Sanatorium\Orders\Validator\Deliverytype\DeliverytypeValidator');
	}

}
