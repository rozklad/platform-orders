<?php namespace Sanatorium\Orders\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Deliverytype\DeliverytypeRepositoryInterface;

class DeliverytypesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Deliverytype\DeliverytypeRepositoryInterface
	 */
	protected $deliverytypes;

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
	 * @param  \Sanatorium\Orders\Repositories\Deliverytype\DeliverytypeRepositoryInterface  $deliverytypes
	 * @return void
	 */
	public function __construct(DeliverytypeRepositoryInterface $deliverytypes)
	{
		parent::__construct();

		$this->deliverytypes = $deliverytypes;
	}

	/**
	 * Display a listing of deliverytype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::deliverytypes.index');
	}

	/**
	 * Datasource for the deliverytype Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->deliverytypes->grid();

		$columns = [
			'id',
			'code',
			'money_min',
			'money_max',
			'delivery_service',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.orders.deliverytypes.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new deliverytype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new deliverytype.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating deliverytype.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating deliverytype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified deliverytype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->deliverytypes->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::deliverytypes/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.deliverytypes.all');
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
				$this->deliverytypes->{$action}($row);
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
		// Do we have a deliverytype identifier?
		if (isset($id))
		{
			if ( ! $deliverytype = $this->deliverytypes->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::deliverytypes/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.deliverytypes.all');
			}
		}
		else
		{
			$deliverytype = $this->deliverytypes->createModel();
		}

		// Show the page
		return view('sanatorium/orders::deliverytypes.form', compact('mode', 'deliverytype'));
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
		// Store the deliverytype
		list($messages) = $this->deliverytypes->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::deliverytypes/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.deliverytypes.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
