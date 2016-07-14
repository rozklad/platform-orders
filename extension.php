<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Orders',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/orders',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Shop orders', 

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.2.1',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
		'sanatorium/shop',
	],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Orders\Providers\OrderServiceProvider',
		'Sanatorium\Orders\Providers\PaymenttypeServiceProvider',
		'Sanatorium\Orders\Providers\DeliverytypeServiceProvider',
		'Sanatorium\Orders\Providers\PaymentServiceProvider',
		'Sanatorium\Orders\Providers\DeliveryServiceProvider',
		'Sanatorium\Orders\Providers\PaymentstatusServiceProvider',
		'Sanatorium\Orders\Providers\DeliverystatusServiceProvider',
		'Sanatorium\Orders\Providers\CustomerServiceProvider',
		'Sanatorium\Orders\Providers\OrderstatusServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group([
				'prefix'    => admin_uri().'/orders/orders',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.orders.all', 'uses' => 'OrdersController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.orders.all', 'uses' => 'OrdersController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.orders.grid', 'uses' => 'OrdersController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.orders.create', 'uses' => 'OrdersController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.orders.create', 'uses' => 'OrdersController@store']);

				Route::post('status', ['as' => 'admin.sanatorium.orders.orders.status', 'uses' => 'OrdersController@status']);

				Route::post('tracking', ['as' => 'admin.sanatorium.orders.orders.tracking', 'uses' => 'OrdersController@tracking']);
				
				Route::post('send', ['as' => 'admin.sanatorium.orders.orders.send', 'uses' => 'OrdersController@send']);

				Route::post('forgot', ['as' => 'admin.sanatorium.orders.orders.forgot', 'uses' => 'OrdersController@forgot']);
				
				Route::post('action', ['as' => 'admin.sanatorium.orders.orders.action', 'uses' => 'OrdersController@action']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.orders.edit'  , 'uses' => 'OrdersController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.orders.edit'  , 'uses' => 'OrdersController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.orders.delete', 'uses' => 'OrdersController@delete']);

				Route::get('export/cpost/{id}', ['as' => 'admin.sanatorium.orders.orders.export.cpost', 'uses' => 'OrdersController@exportCpost']);

				Route::get('export/gls/{id}', ['as' => 'admin.sanatorium.orders.orders.export.gls', 'uses' => 'OrdersController@exportGls']);
			});

		Route::group([
			'prefix'    => 'orders/orders',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.orders.index', 'uses' => 'OrdersController@index']);
			Route::get('{id}', ['as' => 'sanatorium.orders.orders.show', 'uses' => 'OrdersController@show']);
			Route::get('track/{id}', ['as' => 'sanatorium.orders.orders.track', 'uses' => 'OrdersController@track']);
			Route::get('slip/{id}', ['as' => 'sanatorium.orders.orders.slip', 'uses' => 'OrdersController@slip']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/paymenttypes',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.paymenttypes.all', 'uses' => 'PaymenttypesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.paymenttypes.all', 'uses' => 'PaymenttypesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.paymenttypes.grid', 'uses' => 'PaymenttypesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.paymenttypes.create', 'uses' => 'PaymenttypesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.paymenttypes.create', 'uses' => 'PaymenttypesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.paymenttypes.edit'  , 'uses' => 'PaymenttypesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.paymenttypes.edit'  , 'uses' => 'PaymenttypesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.paymenttypes.delete', 'uses' => 'PaymenttypesController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/paymenttypes',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.paymenttypes.index', 'uses' => 'PaymenttypesController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/deliverytypes',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.deliverytypes.all', 'uses' => 'DeliverytypesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.deliverytypes.all', 'uses' => 'DeliverytypesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.deliverytypes.grid', 'uses' => 'DeliverytypesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.deliverytypes.create', 'uses' => 'DeliverytypesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.deliverytypes.create', 'uses' => 'DeliverytypesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.deliverytypes.edit'  , 'uses' => 'DeliverytypesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.deliverytypes.edit'  , 'uses' => 'DeliverytypesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.deliverytypes.delete', 'uses' => 'DeliverytypesController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/deliverytypes',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.deliverytypes.index', 'uses' => 'DeliverytypesController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/payments',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.payments.all', 'uses' => 'PaymentsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.payments.all', 'uses' => 'PaymentsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.payments.grid', 'uses' => 'PaymentsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.payments.create', 'uses' => 'PaymentsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.payments.create', 'uses' => 'PaymentsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.payments.edit'  , 'uses' => 'PaymentsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.payments.edit'  , 'uses' => 'PaymentsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.payments.delete', 'uses' => 'PaymentsController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/payments',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.payments.index', 'uses' => 'PaymentsController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/deliveries',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.deliveries.all', 'uses' => 'DeliveriesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.deliveries.all', 'uses' => 'DeliveriesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.deliveries.grid', 'uses' => 'DeliveriesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.deliveries.create', 'uses' => 'DeliveriesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.deliveries.create', 'uses' => 'DeliveriesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.deliveries.edit'  , 'uses' => 'DeliveriesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.deliveries.edit'  , 'uses' => 'DeliveriesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.deliveries.delete', 'uses' => 'DeliveriesController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/deliveries',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.deliveries.index', 'uses' => 'DeliveriesController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/paymentstatuses',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.paymentstatuses.all', 'uses' => 'PaymentstatusesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.paymentstatuses.all', 'uses' => 'PaymentstatusesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.paymentstatuses.grid', 'uses' => 'PaymentstatusesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.paymentstatuses.create', 'uses' => 'PaymentstatusesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.paymentstatuses.create', 'uses' => 'PaymentstatusesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.paymentstatuses.edit'  , 'uses' => 'PaymentstatusesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.paymentstatuses.edit'  , 'uses' => 'PaymentstatusesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.paymentstatuses.delete', 'uses' => 'PaymentstatusesController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/paymentstatuses',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.paymentstatuses.index', 'uses' => 'PaymentstatusesController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/orders/deliverystatuses',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.deliverystatuses.all', 'uses' => 'DeliverystatusesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.deliverystatuses.all', 'uses' => 'DeliverystatusesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.deliverystatuses.grid', 'uses' => 'DeliverystatusesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.deliverystatuses.create', 'uses' => 'DeliverystatusesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.deliverystatuses.create', 'uses' => 'DeliverystatusesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.deliverystatuses.edit'  , 'uses' => 'DeliverystatusesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.deliverystatuses.edit'  , 'uses' => 'DeliverystatusesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.deliverystatuses.delete', 'uses' => 'DeliverystatusesController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/deliverystatuses',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.deliverystatuses.index', 'uses' => 'DeliverystatusesController@index']);
		});

		$cart_routes = function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.cart.index', 'uses' => 'CartController@index']);

			// Steps
			Route::any('delivery', ['as' => 'sanatorium.orders.cart.delivery', 'uses' => 'CartController@delivery']);
			Route::any('user', ['as' => 'sanatorium.orders.cart.user', 'uses' => 'CartController@user']);
			Route::get('confirm', ['as' => 'sanatorium.orders.cart.confirm', 'uses' => 'CartController@confirm']);
			Route::post('confirm', ['as' => 'sanatorium.orders.cart.confirmed', 'uses' => 'CartController@confirmed']);


			Route::any('add', ['as' => 'sanatorium.orders.cart.add', 'uses' => 'CartController@add']);

			Route::any('delete', ['as' => 'sanatorium.orders.cart.delete', 'uses' => 'CartController@delete']);

			Route::any('update', ['as' => 'sanatorium.orders.cart.update', 'uses' => 'CartController@update']);

			Route::any('prices', ['as' => 'sanatorium.orders.cart.prices', 'uses' => 'CartController@prices']);
		
			Route::any('clear', ['as' => 'sanatorium.orders.cart.clear', 'uses' => 'CartController@clear']);

			Route::any('place', ['as' => 'sanatorium.orders.cart.place', 'uses' => 'CartController@place']);

			Route::any('placed', ['as' => 'sanatorium.orders.cart.placed', 'uses' => 'CartController@placed']);

			Route::get('debug', ['as' => 'sanatorium.orders.cart.debug', 'uses' => 'CartController@debug']);
		};

		Route::group([
			'prefix'    => 'cart',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], $cart_routes);

		Route::group([
			'prefix'    => trans('sanatorium/orders::cart.url'),
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], $cart_routes);

		Route::group([
				'prefix'    => admin_uri().'/orders/customers',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.customers.all', 'uses' => 'CustomersController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.customers.all', 'uses' => 'CustomersController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.customers.grid', 'uses' => 'CustomersController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.customers.create', 'uses' => 'CustomersController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.customers.create', 'uses' => 'CustomersController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.customers.edit'  , 'uses' => 'CustomersController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.customers.edit'  , 'uses' => 'CustomersController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.customers.delete', 'uses' => 'CustomersController@delete']);
			});

		Route::group([
			'prefix'    => 'orders/customers',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.customers.index', 'uses' => 'CustomersController@index']);
		});
			/*
					Route::group([
				'prefix'    => admin_uri().'/orders/orderstatuses',
				'namespace' => 'Sanatorium\Orders\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.orders.orderstatuses.all', 'uses' => 'OrderstatusesController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.orders.orderstatuses.all', 'uses' => 'OrderstatusesController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.orders.orderstatuses.grid', 'uses' => 'OrderstatusesController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.orders.orderstatuses.create', 'uses' => 'OrderstatusesController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.orders.orderstatuses.create', 'uses' => 'OrderstatusesController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.orders.orderstatuses.edit'  , 'uses' => 'OrderstatusesController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.orders.orderstatuses.edit'  , 'uses' => 'OrderstatusesController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.orders.orderstatuses.delete', 'uses' => 'OrderstatusesController@delete']);
			});
			*/

		Route::group([
			'prefix'    => 'orders/orderstatuses',
			'namespace' => 'Sanatorium\Orders\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.orders.orderstatuses.index', 'uses' => 'OrderstatusesController@index']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

		'Sanatorium\Orders\Database\Seeds\DefaultTableSeeder',

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('order', function($g)
		{
			$g->name = 'Orders';

			$g->permission('order.index', function($p)
			{
				$p->label = trans('sanatorium/orders::orders/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrdersController', 'index, grid');
			});

			$g->permission('order.create', function($p)
			{
				$p->label = trans('sanatorium/orders::orders/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrdersController', 'create, store');
			});

			$g->permission('order.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::orders/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrdersController', 'edit, update');
			});

			$g->permission('order.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::orders/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrdersController', 'delete');
			});
		});

		$permissions->group('paymenttype', function($g)
		{
			$g->name = 'Paymenttypes';

			$g->permission('paymenttype.index', function($p)
			{
				$p->label = trans('sanatorium/orders::paymenttypes/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymenttypesController', 'index, grid');
			});

			$g->permission('paymenttype.create', function($p)
			{
				$p->label = trans('sanatorium/orders::paymenttypes/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymenttypesController', 'create, store');
			});

			$g->permission('paymenttype.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::paymenttypes/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymenttypesController', 'edit, update');
			});

			$g->permission('paymenttype.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::paymenttypes/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymenttypesController', 'delete');
			});
		});

		$permissions->group('deliverytype', function($g)
		{
			$g->name = 'Deliverytypes';

			$g->permission('deliverytype.index', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverytypes/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverytypesController', 'index, grid');
			});

			$g->permission('deliverytype.create', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverytypes/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverytypesController', 'create, store');
			});

			$g->permission('deliverytype.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverytypes/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverytypesController', 'edit, update');
			});

			$g->permission('deliverytype.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverytypes/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverytypesController', 'delete');
			});
		});

		$permissions->group('payment', function($g)
		{
			$g->name = 'Payments';

			$g->permission('payment.index', function($p)
			{
				$p->label = trans('sanatorium/orders::payments/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentsController', 'index, grid');
			});

			$g->permission('payment.create', function($p)
			{
				$p->label = trans('sanatorium/orders::payments/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentsController', 'create, store');
			});

			$g->permission('payment.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::payments/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentsController', 'edit, update');
			});

			$g->permission('payment.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::payments/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentsController', 'delete');
			});
		});

		$permissions->group('delivery', function($g)
		{
			$g->name = 'Deliveries';

			$g->permission('delivery.index', function($p)
			{
				$p->label = trans('sanatorium/orders::deliveries/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliveriesController', 'index, grid');
			});

			$g->permission('delivery.create', function($p)
			{
				$p->label = trans('sanatorium/orders::deliveries/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliveriesController', 'create, store');
			});

			$g->permission('delivery.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::deliveries/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliveriesController', 'edit, update');
			});

			$g->permission('delivery.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::deliveries/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliveriesController', 'delete');
			});
		});

		$permissions->group('paymentstatus', function($g)
		{
			$g->name = 'Paymentstatuses';

			$g->permission('paymentstatus.index', function($p)
			{
				$p->label = trans('sanatorium/orders::paymentstatuses/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentstatusesController', 'index, grid');
			});

			$g->permission('paymentstatus.create', function($p)
			{
				$p->label = trans('sanatorium/orders::paymentstatuses/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentstatusesController', 'create, store');
			});

			$g->permission('paymentstatus.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::paymentstatuses/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentstatusesController', 'edit, update');
			});

			$g->permission('paymentstatus.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::paymentstatuses/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\PaymentstatusesController', 'delete');
			});
		});

		$permissions->group('deliverystatus', function($g)
		{
			$g->name = 'Deliverystatuses';

			$g->permission('deliverystatus.index', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverystatuses/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverystatusesController', 'index, grid');
			});

			$g->permission('deliverystatus.create', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverystatuses/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverystatusesController', 'create, store');
			});

			$g->permission('deliverystatus.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverystatuses/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverystatusesController', 'edit, update');
			});

			$g->permission('deliverystatus.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::deliverystatuses/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\DeliverystatusesController', 'delete');
			});
		});

		$permissions->group('customer', function($g)
		{
			$g->name = 'Customers';

			$g->permission('customer.index', function($p)
			{
				$p->label = trans('sanatorium/orders::customers/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\CustomersController', 'index, grid');
			});

			$g->permission('customer.create', function($p)
			{
				$p->label = trans('sanatorium/orders::customers/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\CustomersController', 'create, store');
			});

			$g->permission('customer.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::customers/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\CustomersController', 'edit, update');
			});

			$g->permission('customer.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::customers/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\CustomersController', 'delete');
			});
		});

		$permissions->group('orderstatus', function($g)
		{
			$g->name = 'Orderstatuses';

			$g->permission('orderstatus.index', function($p)
			{
				$p->label = trans('sanatorium/orders::orderstatuses/permissions.index');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrderstatusesController', 'index, grid');
			});

			$g->permission('orderstatus.create', function($p)
			{
				$p->label = trans('sanatorium/orders::orderstatuses/permissions.create');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrderstatusesController', 'create, store');
			});

			$g->permission('orderstatus.edit', function($p)
			{
				$p->label = trans('sanatorium/orders::orderstatuses/permissions.edit');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrderstatusesController', 'edit, update');
			});

			$g->permission('orderstatus.delete', function($p)
			{
				$p->label = trans('sanatorium/orders::orderstatuses/permissions.delete');

				$p->controller('Sanatorium\Orders\Controllers\Admin\OrderstatusesController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{
		$settings->find('platform')->section('orders', function ($s) {
			$s->name = trans('sanatorium/orders::settings.title');

            $s->fieldset('orders', function ($f) {
                $f->name = trans('sanatorium/orders::settings.title');

                $f->field('only_logged_in', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.only_logged_in.label');
                    $f->info   = trans('sanatorium/orders::settings.only_logged_in.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.only_logged_in';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/orders::settings.only_logged_in.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/orders::settings.only_logged_in.values.false');
                    });
                });

                $f->field('cart_mode', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.cart_mode.label');
                    $f->info   = trans('sanatorium/orders::settings.cart_mode.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.cart_mode';

                    $f->option('single', function ($o) {
                        $o->value = 'single';
                        $o->label = trans('sanatorium/orders::settings.cart_mode.values.single');
                    });

                    $f->option('steps', function ($o) {
                        $o->value = 'steps';
                        $o->label = trans('sanatorium/orders::settings.cart_mode.values.steps');
                    });
                });

                $f->field('async', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.async.label');
                    $f->info   = trans('sanatorium/orders::settings.async.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.async';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/orders::settings.async.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/orders::settings.async.values.false');
                    });
                });

                $f->field('ff_delivery_payment', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.ff_delivery_payment.label');
                    $f->info   = trans('sanatorium/orders::settings.ff_delivery_payment.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.ff_delivery_payment';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/orders::settings.ff_delivery_payment.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/orders::settings.ff_delivery_payment.values.false');
                    });
                });

                $f->field('show_slips', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.show_slips.label');
                    $f->info   = trans('sanatorium/orders::settings.show_slips.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.show_slips';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/orders::settings.show_slips.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/orders::settings.show_slips.values.false');
                    });
                });

                $f->field('guess_country', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.guess_country.label');
                    $f->info   = trans('sanatorium/orders::settings.guess_country.info');
                    $f->type   = 'radio';
                    $f->config = 'sanatorium-orders.guess_country';

                    $f->option(1, function ($o) {
                        $o->value = 1;
                        $o->label = trans('sanatorium/orders::settings.guess_country.values.true');
                    });

                    $f->option(0, function ($o) {
                        $o->value = 0;
                        $o->label = trans('sanatorium/orders::settings.guess_country.values.false');
                    });
                });

                $f->field('netimpact_key', function ($f) {
                    $f->name   = trans('sanatorium/orders::settings.netimpact_key.label');
                    $f->info   = trans('sanatorium/orders::settings.netimpact_key.info');
                    $f->type   = 'input';
                    $f->config = 'sanatorium-orders.netimpact_key';
                });
            });
        });
	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-orders',
				'name' => 'Orders',
				'class' => 'fa fa-sticky-note',
				'uri' => 'orders',
				'regex' => '/:admin\/orders/i',
				'children' => [
					[
						'class' => 'fa fa-sticky-note',
						'name' => 'Orders',
						'uri' => 'orders/orders',
						'regex' => '/:admin\/orders\/order/i',
						'slug' => 'admin-sanatorium-orders-order',
					],
					[
						'class' => 'fa fa-credit-card-alt',
						'name' => 'Paymenttypes',
						'uri' => 'orders/paymenttypes',
						'regex' => '/:admin\/orders\/paymenttype/i',
						'slug' => 'admin-sanatorium-orders-paymenttype',
					],
					[
						'class' => 'fa fa-truck',
						'name' => 'Deliverytypes',
						'uri' => 'orders/deliverytypes',
						'regex' => '/:admin\/orders\/deliverytype/i',
						'slug' => 'admin-sanatorium-orders-deliverytype',
					],
					[
						'class' => 'fa fa-money',
						'name' => 'Payments',
						'uri' => 'orders/payments',
						'regex' => '/:admin\/orders\/payment/i',
						'slug' => 'admin-sanatorium-orders-payment',
					],
					[
						'class' => 'fa fa-truck',
						'name' => 'Deliveries',
						'uri' => 'orders/deliveries',
						'regex' => '/:admin\/orders\/delivery/i',
						'slug' => 'admin-sanatorium-orders-delivery',
					],
					[
						'class' => 'fa fa-toggle-on',
						'name' => 'Paymentstatuses',
						'uri' => 'orders/paymentstatuses',
						'regex' => '/:admin\/orders\/paymentstatus/i',
						'slug' => 'admin-sanatorium-orders-paymentstatus',
					],
					[
						'class' => 'fa fa-toggle-off',
						'name' => 'Deliverystatuses',
						'uri' => 'orders/deliverystatuses',
						'regex' => '/:admin\/orders\/deliverystatus/i',
						'slug' => 'admin-sanatorium-orders-deliverystatus',
					],
					[
						'class' => 'fa fa-users',
						'name' => 'Customers',
						'uri' => 'orders/customers',
						'regex' => '/:admin\/orders\/customer/i',
						'slug' => 'admin-sanatorium-orders-customer',
					],
					[
						'class' => 'fa fa-tags',
						'name' => 'Orderstatuses',
						'uri' => 'orders/orderstatuses',
						'regex' => '/:admin\/orders\/orderstatus/i',
						'slug' => 'admin-sanatorium-orders-orderstatus',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
