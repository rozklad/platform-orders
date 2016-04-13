<?php namespace Sanatorium\Orders\Repositories\Paymentstatus;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class PaymentstatusRepository implements PaymentstatusRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Paymentstatus\PaymentstatusDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.paymentstatus.handler.data'];

		$this->setValidator($app['sanatorium.orders.paymentstatus.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Paymentstatus']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.paymentstatus.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.paymentstatus.'.$id, function() use ($id)
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
		// Create a new paymentstatus
		$paymentstatus = $this->createModel();

		// Fire the 'sanatorium.orders.paymentstatus.creating' event
		if ($this->fireEvent('sanatorium.orders.paymentstatus.creating', [ $input ]) === false)
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
			// Save the paymentstatus
			$paymentstatus->fill($data)->save();

			// Fire the 'sanatorium.orders.paymentstatus.created' event
			$this->fireEvent('sanatorium.orders.paymentstatus.created', [ $paymentstatus ]);
		}

		return [ $messages, $paymentstatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the paymentstatus object
		$paymentstatus = $this->find($id);

		// Fire the 'sanatorium.orders.paymentstatus.updating' event
		if ($this->fireEvent('sanatorium.orders.paymentstatus.updating', [ $paymentstatus, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($paymentstatus, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the paymentstatus
			$paymentstatus->fill($data)->save();

			// Fire the 'sanatorium.orders.paymentstatus.updated' event
			$this->fireEvent('sanatorium.orders.paymentstatus.updated', [ $paymentstatus ]);
		}

		return [ $messages, $paymentstatus ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the paymentstatus exists
		if ($paymentstatus = $this->find($id))
		{
			// Fire the 'sanatorium.orders.paymentstatus.deleted' event
			$this->fireEvent('sanatorium.orders.paymentstatus.deleted', [ $paymentstatus ]);

			// Delete the paymentstatus entry
			$paymentstatus->delete();

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
