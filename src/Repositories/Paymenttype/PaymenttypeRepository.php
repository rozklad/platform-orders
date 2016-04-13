<?php namespace Sanatorium\Orders\Repositories\Paymenttype;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class PaymenttypeRepository implements PaymenttypeRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Paymenttype\PaymenttypeDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.paymenttype.handler.data'];

		$this->setValidator($app['sanatorium.orders.paymenttype.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Paymenttype']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.paymenttype.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.paymenttype.'.$id, function() use ($id)
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
		// Create a new paymenttype
		$paymenttype = $this->createModel();

		// Fire the 'sanatorium.orders.paymenttype.creating' event
		if ($this->fireEvent('sanatorium.orders.paymenttype.creating', [ $input ]) === false)
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
			// Save the paymenttype
			$paymenttype->fill($data)->save();

			// Fire the 'sanatorium.orders.paymenttype.created' event
			$this->fireEvent('sanatorium.orders.paymenttype.created', [ $paymenttype ]);
		}

		return [ $messages, $paymenttype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the paymenttype object
		$paymenttype = $this->find($id);

		// Fire the 'sanatorium.orders.paymenttype.updating' event
		if ($this->fireEvent('sanatorium.orders.paymenttype.updating', [ $paymenttype, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($paymenttype, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the paymenttype
			$paymenttype->fill($data)->save();

			// Fire the 'sanatorium.orders.paymenttype.updated' event
			$this->fireEvent('sanatorium.orders.paymenttype.updated', [ $paymenttype ]);
		}

		return [ $messages, $paymenttype ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the paymenttype exists
		if ($paymenttype = $this->find($id))
		{
			// Fire the 'sanatorium.orders.paymenttype.deleted' event
			$this->fireEvent('sanatorium.orders.paymenttype.deleted', [ $paymenttype ]);

			// Delete the paymenttype entry
			$paymenttype->delete();

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
