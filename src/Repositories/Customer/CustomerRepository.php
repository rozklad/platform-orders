<?php namespace Sanatorium\Orders\Repositories\Customer;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class CustomerRepository implements CustomerRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Customer\CustomerDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.customer.handler.data'];

		$this->setValidator($app['sanatorium.orders.customer.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Customer']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.customer.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.customer.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
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
		// Create a new customer
		$customer = $this->createModel();

		// Fire the 'sanatorium.orders.customer.creating' event
		if ($this->fireEvent('sanatorium.orders.customer.creating', [ $input ]) === false)
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
			// Save the customer
			$customer->fill($data)->save();

			// Fire the 'sanatorium.orders.customer.created' event
			$this->fireEvent('sanatorium.orders.customer.created', [ $customer ]);
		}

		return [ $messages, $customer ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the customer object
		$customer = $this->find($id);

		// Fire the 'sanatorium.orders.customer.updating' event
		if ($this->fireEvent('sanatorium.orders.customer.updating', [ $customer, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($customer, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the customer
			$customer->fill($data)->save();

			// Fire the 'sanatorium.orders.customer.updated' event
			$this->fireEvent('sanatorium.orders.customer.updated', [ $customer ]);
		}

		return [ $messages, $customer ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the customer exists
		if ($customer = $this->find($id))
		{
			// Fire the 'sanatorium.orders.customer.deleted' event
			$this->fireEvent('sanatorium.orders.customer.deleted', [ $customer ]);

			// Delete the customer entry
			$customer->delete();

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

}
