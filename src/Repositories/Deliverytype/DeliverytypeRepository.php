<?php namespace Sanatorium\Orders\Repositories\Deliverytype;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class DeliverytypeRepository implements DeliverytypeRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Deliverytype\DeliverytypeDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.deliverytype.handler.data'];

		$this->setValidator($app['sanatorium.orders.deliverytype.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Deliverytype']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.deliverytype.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.deliverytype.'.$id, function() use ($id)
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
		// Create a new deliverytype
		$deliverytype = $this->createModel();

		// Fire the 'sanatorium.orders.deliverytype.creating' event
		if ($this->fireEvent('sanatorium.orders.deliverytype.creating', [ $input ]) === false)
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
			// Save the deliverytype
			$deliverytype->fill($data)->save();

			// Fire the 'sanatorium.orders.deliverytype.created' event
			$this->fireEvent('sanatorium.orders.deliverytype.created', [ $deliverytype ]);
		}

		return [ $messages, $deliverytype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the deliverytype object
		$deliverytype = $this->find($id);

		// Fire the 'sanatorium.orders.deliverytype.updating' event
		if ($this->fireEvent('sanatorium.orders.deliverytype.updating', [ $deliverytype, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($deliverytype, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the deliverytype
			$deliverytype->fill($data)->save();

			// Fire the 'sanatorium.orders.deliverytype.updated' event
			$this->fireEvent('sanatorium.orders.deliverytype.updated', [ $deliverytype ]);
		}

		return [ $messages, $deliverytype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the deliverytype exists
		if ($deliverytype = $this->find($id))
		{
			// Fire the 'sanatorium.orders.deliverytype.deleted' event
			$this->fireEvent('sanatorium.orders.deliverytype.deleted', [ $deliverytype ]);

			// Delete the deliverytype entry
			$deliverytype->delete();

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
