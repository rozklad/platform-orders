<?php namespace Sanatorium\Orders\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Delivery\DeliveryRepositoryInterface;

class DeliveriesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Delivery\DeliveryRepositoryInterface
	 */
	protected $deliveries;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Orders\Repositories\Delivery\DeliveryRepositoryInterface  $deliveries
	 * @return void
	 */
	public function __construct(DeliveryRepositoryInterface $deliveries)
	{
		parent::__construct();

		$this->deliveries = $deliveries;
	}

	/**
	 * Display a listing of delivery.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::deliveries.index');
	}

	/**
	 * Datasource for the delivery Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->deliveries->grid();

		$columns = [
			'id',
			'delivery_status_id',
			'delivery_type_id',
			'delivery_money_id',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.orders.deliveries.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new delivery.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new delivery.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating delivery.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating delivery.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified delivery.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->deliveries->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::deliveries/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.deliveries.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->deliveries->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a delivery identifier?
		if (isset($id))
		{
			if ( ! $delivery = $this->deliveries->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::deliveries/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.deliveries.all');
			}
		}
		else
		{
			$delivery = $this->deliveries->createModel();
		}

		// Show the page
		return view('sanatorium/orders::deliveries.form', compact('mode', 'delivery'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the delivery
		list($messages) = $this->deliveries->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::deliveries/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.deliveries.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
