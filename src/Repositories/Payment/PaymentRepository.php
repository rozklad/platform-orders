<?php namespace Sanatorium\Orders\Repositories\Payment;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class PaymentRepository implements PaymentRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Orders\Handlers\Payment\PaymentDataHandlerInterface
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

		$this->data = $app['sanatorium.orders.payment.handler.data'];

		$this->setValidator($app['sanatorium.orders.payment.validator']);

		$this->setModel(get_class($app['Sanatorium\Orders\Models\Payment']));
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
		return $this->container['cache']->rememberForever('sanatorium.orders.payment.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.orders.payment.'.$id, function() use ($id)
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
		// Create a new payment
		$payment = $this->createModel();

		// Fire the 'sanatorium.orders.payment.creating' event
		if ($this->fireEvent('sanatorium.orders.payment.creating', [ $input ]) === false)
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
			// Save the payment
			$payment->fill($data)->save();

			// Fire the 'sanatorium.orders.payment.created' event
			$this->fireEvent('sanatorium.orders.payment.created', [ $payment ]);
		}

		return [ $messages, $payment ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the payment object
		$payment = $this->find($id);

		// Fire the 'sanatorium.orders.payment.updating' event
		if ($this->fireEvent('sanatorium.orders.payment.updating', [ $payment, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($payment, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the payment
			$payment->fill($data)->save();

			// Fire the 'sanatorium.orders.payment.updated' event
			$this->fireEvent('sanatorium.orders.payment.updated', [ $payment ]);
		}

		return [ $messages, $payment ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the payment exists
		if ($payment = $this->find($id))
		{
			// Fire the 'sanatorium.orders.payment.deleted' event
			$this->fireEvent('sanatorium.orders.payment.deleted', [ $payment ]);

			// Delete the payment entry
			$payment->delete();

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
