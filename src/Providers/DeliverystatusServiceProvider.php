<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class DeliverystatusServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Deliverystatus']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.deliverystatus.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.deliverystatus', 'Sanatorium\Orders\Repositories\Deliverystatus\DeliverystatusRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.deliverystatus.handler.data', 'Sanatorium\Orders\Handlers\Deliverystatus\DeliverystatusDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.deliverystatus.handler.event', 'Sanatorium\Orders\Handlers\Deliverystatus\DeliverystatusEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.deliverystatus.validator', 'Sanatorium\Orders\Validator\Deliverystatus\DeliverystatusValidator');
	}

}
