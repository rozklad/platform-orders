<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class PaymentstatusServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Paymentstatus']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.paymentstatus.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.paymentstatus', 'Sanatorium\Orders\Repositories\Paymentstatus\PaymentstatusRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.paymentstatus.handler.data', 'Sanatorium\Orders\Handlers\Paymentstatus\PaymentstatusDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.paymentstatus.handler.event', 'Sanatorium\Orders\Handlers\Paymentstatus\PaymentstatusEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.paymentstatus.validator', 'Sanatorium\Orders\Validator\Paymentstatus\PaymentstatusValidator');
	}

}
