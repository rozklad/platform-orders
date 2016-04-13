<?php namespace Sanatorium\Orders\Repositories\Orderstatus;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class OrderstatusRepository implements OrderstatusRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Orderstatus\OrderstatusDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.orderstatus.handler.data'];

		$this->setValidator($app['sanatorium.orders.orderstatus.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Orderstatus']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.orderstatus.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.orderstatus.'.$id, function() use ($id)
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
		// Create a new orderstatus
		$orderstatus = $this->createModel();

		// Fire the 'sanatorium.orders.orderstatus.creating' event
		if ($this->fireEvent('sanatorium.orders.orderstatus.creating', [ $input ]) === false)
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
			// Save the orderstatus
			$orderstatus->fill($data)->save();

			// Fire the 'sanatorium.orders.orderstatus.created' event
			$this->fireEvent('sanatorium.orders.orderstatus.created', [ $orderstatus ]);
		}

		return [ $messages, $orderstatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the orderstatus object
		$orderstatus = $this->find($id);

		// Fire the 'sanatorium.orders.orderstatus.updating' event
		if ($this->fireEvent('sanatorium.orders.orderstatus.updating', [ $orderstatus, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($orderstatus, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the orderstatus
			$orderstatus->fill($data)->save();

			// Fire the 'sanatorium.orders.orderstatus.updated' event
			$this->fireEvent('sanatorium.orders.orderstatus.updated', [ $orderstatus ]);
		}

		return [ $messages, $orderstatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the orderstatus exists
		if ($orderstatus = $this->find($id))
		{
			// Fire the 'sanatorium.orders.orderstatus.deleted' event
			$this->fireEvent('sanatorium.orders.orderstatus.deleted', [ $orderstatus ]);

			// Delete the orderstatus entry
			$orderstatus->delete();

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
