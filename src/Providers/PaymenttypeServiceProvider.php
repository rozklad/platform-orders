<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class PaymenttypeServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Paymenttype']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.paymenttype.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.paymenttype', 'Sanatorium\Orders\Repositories\Paymenttype\PaymenttypeRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.paymenttype.handler.data', 'Sanatorium\Orders\Handlers\Paymenttype\PaymenttypeDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.paymenttype.handler.event', 'Sanatorium\Orders\Handlers\Paymenttype\PaymenttypeEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.paymenttype.validator', 'Sanatorium\Orders\Validator\Paymenttype\PaymenttypeValidator');
	}

}
