<?php namespace Sanatorium\Orders\Repositories\Deliverystatus;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class DeliverystatusRepository implements DeliverystatusRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Deliverystatus\DeliverystatusDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.deliverystatus.handler.data'];

		$this->setValidator($app['sanatorium.orders.deliverystatus.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Deliverystatus']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.deliverystatus.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.deliverystatus.'.$id, function() use ($id)
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
		// Create a new deliverystatus
		$deliverystatus = $this->createModel();

		// Fire the 'sanatorium.orders.deliverystatus.creating' event
		if ($this->fireEvent('sanatorium.orders.deliverystatus.creating', [ $input ]) === false)
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
			// Save the deliverystatus
			$deliverystatus->fill($data)->save();

			// Fire the 'sanatorium.orders.deliverystatus.created' event
			$this->fireEvent('sanatorium.orders.deliverystatus.created', [ $deliverystatus ]);
		}

		return [ $messages, $deliverystatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the deliverystatus object
		$deliverystatus = $this->find($id);

		// Fire the 'sanatorium.orders.deliverystatus.updating' event
		if ($this->fireEvent('sanatorium.orders.deliverystatus.updating', [ $deliverystatus, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($deliverystatus, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the deliverystatus
			$deliverystatus->fill($data)->save();

			// Fire the 'sanatorium.orders.deliverystatus.updated' event
			$this->fireEvent('sanatorium.orders.deliverystatus.updated', [ $deliverystatus ]);
		}

		return [ $messages, $deliverystatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the deliverystatus exists
		if ($deliverystatus = $this->find($id))
		{
			// Fire the 'sanatorium.orders.deliverystatus.deleted' event
			$this->fireEvent('sanatorium.orders.deliverystatus.deleted', [ $deliverystatus ]);

			// Delete the deliverystatus entry
			$deliverystatus->delete();

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
