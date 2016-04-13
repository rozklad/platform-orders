<?php namespace Sanatorium\Orders\Repositories\Order;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class OrderRepository implements OrderRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Order\OrderDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent orders model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.orders.order.handler.data'];

		$this->setValidator($app['sanatorium.orders.order.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Order']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.order.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		// cache disabled
		//return $this->container['cache']->rememberForever('sanatorium.orders.order.'.$id, function() use ($id)
		//{
			return $this->createModel()->find($id);
		//});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new order
		$order = $this->createModel();

		// Fire the 'sanatorium.orders.order.creating' event
		if ($this->fireEvent('sanatorium.orders.order.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the order
			$order->fill($data)->save();

			// Fire the 'sanatorium.orders.order.created' event
			$this->fireEvent('sanatorium.orders.order.created', [ $order ]);
		}

		return [ $messages, $order ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the order object
		$order = $this->find($id);

		// Fire the 'sanatorium.orders.order.updating' event
		if ($this->fireEvent('sanatorium.orders.order.updating', [ $order, $input ]) === false)
		{
			return false;
		}

		// Store updated delivery address
		$delivery = array_pull($input, 'delivery');

		$deliveryAddress = \Sanatorium\Addresses\Models\Address::find($order->address_delivery_id);

		$deliveryAddress->fill($delivery)->save();

		// Store updated billing address
		$billing = array_pull($input, 'billing');

		$billingAddress = \Sanatorium\Addresses\Models\Address::find($order->address_billing_id);

		$billingAddress->fill($billing)->save();

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($order, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the order
			$order->fill($data)->save();

			// Fire the 'sanatorium.orders.order.updated' event
			$this->fireEvent('sanatorium.orders.order.updated', [ $order ]);
		}

		return [ $messages, $order ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the order exists
		if ($order = $this->find($id))
		{
			// Fire the 'sanatorium.orders.order.deleted' event
			$this->fireEvent('sanatorium.orders.order.deleted', [ $order ]);

			// Delete the order entry
			$order->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

	public function customer_repair($id)
	{
		// Get the order object
		$order = $this->find($id);

		$this->customers = app('sanatorium.orders.customer');

		$this->users = app('platform.users');

		// Identify customer or create one
		if ( $customer = $this->customers->where('email', $order->order_email)->first() ) {
			$order->customer_id = $customer->id;
		} else {
			list($messages, $customer) = $this->customers->create([
				'email' => $order->order_email,
				'user_id' => $order->user_id ? $order->user_id : null
				]);
			$order->customer_id = $customer->id;
		}

		// Add user id to customer, if available
		if ( !$customer->user_id ) {
			$user = $this->users->where('email', $order->order_email)->first();

			if ( $user ) {
				$customer->user_id = $user->id;
				$customer->save();
			}
		}

		$order->save();

		$messages = [];

		return [ $messages, $order ];
	}

}
