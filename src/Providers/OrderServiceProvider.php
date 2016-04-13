<?php namespace Sanatorium\Orders\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class OrderServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Orders\Models\Order']
		);

		// Register the statusable
		$this->app['sanatorium.status.manager']->registerStatusable(
			'Sanatorium\Orders\Models\Order'
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.orders.order.handler.event');

		// Register cart package
		$this->registerCartalystCartPackage();

		// Register all the default hooks
        $this->registerHooks();

        // Prepare resources
        $this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.orders.order', 'Sanatorium\Orders\Repositories\Order\OrderRepository');

		// Register the data handler
		$this->bindIf('sanatorium.orders.order.handler.data', 'Sanatorium\Orders\Handlers\Order\OrderDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.orders.order.handler.event', 'Sanatorium\Orders\Handlers\Order\OrderEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.orders.order.validator', 'Sanatorium\Orders\Validator\Order\OrderValidator');
	}

	/**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-orders');

        $this->publishes([
            $config => config_path('sanatorium-orders.php'),
        ], 'config');
    }

	/**
	 * Register cartalyst/cart package
	 * @return
	 */
	protected function registerCartalystCartPackage() 
	{
		$serviceProvider = 'Cartalyst\Cart\Laravel\CartServiceProvider';

		if (!$this->app->getProvider($serviceProvider)) {
			$this->app->register($serviceProvider);
			AliasLoader::getInstance()->alias('Cart', 'Cartalyst\Cart\Laravel\Facades\Cart');
		}
	}

	/**
     * Register all hooks.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $hooks = [
            [
            	'position' => 'cart.show',
            	'hook' => 'sanatorium/orders::hooks.cart',
            ],
            [
            	'position' => 'catalog.product.bottom',
            	'hook' => 'sanatorium/orders::hooks.buy',
            ],
        ];

        if ( config('sanatorium-orders.async') ) {
        	$hooks[] = [
        		'position' => 'shop.footer',
        		'hook' => 'sanatorium/orders::hooks.async'
        	];
        } 

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $item) {
        	extract($item);
            $manager->registerHook($position, $hook);
        }
    }
}
