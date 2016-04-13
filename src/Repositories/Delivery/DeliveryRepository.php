<?php namespace Sanatorium\Orders\Repositories\Delivery;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class DeliveryRepository implements DeliveryRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Delivery\DeliveryDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.delivery.handler.data'];

		$this->setValidator($app['sanatorium.orders.delivery.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Delivery']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.delivery.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.delivery.'.$id, function() use ($id)
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
		// Create a new delivery
		$delivery = $this->createModel();

		// Fire the 'sanatorium.orders.delivery.creating' event
		if ($this->fireEvent('sanatorium.orders.delivery.creating', [ $input ]) === false)
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
			// Save the delivery
			$delivery->fill($data)->save();

			// Fire the 'sanatorium.orders.delivery.created' event
			$this->fireEvent('sanatorium.orders.delivery.created', [ $delivery ]);
		}

		return [ $messages, $delivery ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the delivery object
		$delivery = $this->find($id);

		// Fire the 'sanatorium.orders.delivery.updating' event
		if ($this->fireEvent('sanatorium.orders.delivery.updating', [ $delivery, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($delivery, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the delivery
			$delivery->fill($data)->save();

			// Fire the 'sanatorium.orders.delivery.updated' event
			$this->fireEvent('sanatorium.orders.delivery.updated', [ $delivery ]);
		}

		return [ $messages, $delivery ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the delivery exists
		if ($delivery = $this->find($id))
		{
			// Fire the 'sanatorium.orders.delivery.deleted' event
			$this->fireEvent('sanatorium.orders.delivery.deleted', [ $delivery ]);

			// Delete the delivery entry
			$delivery->delete();

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
