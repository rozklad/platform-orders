<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Payment']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.payment.handler.event');

		// Register the default payment service
		$this->app['sanatorium.orders.payment.services']->registerService(
			'\Sanatorium\Orders\Controllers\Services\DefaultPaymentService'
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.payment', 'Sanatorium\Orders\Repositories\Payment\PaymentRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.payment.handler.data', 'Sanatorium\Orders\Handlers\Payment\PaymentDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.payment.handler.event', 'Sanatorium\Orders\Handlers\Payment\PaymentEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.payment.validator', 'Sanatorium\Orders\Validator\Payment\PaymentValidator');
	
		// Register the payment services manager
		$this->bindIf('sanatorium.orders.payment.services', 'Sanatorium\Orders\Repositories\PaymentServiceRepository');
	
	}

}
