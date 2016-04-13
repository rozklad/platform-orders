<?php namespace Sanatorium\Orders\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Deliverystatus\DeliverystatusRepositoryInterface;

class DeliverystatusesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Deliverystatus\DeliverystatusRepositoryInterface
	 */
	protected $deliverystatuses;

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
	 * @param  \Sanatorium\Orders\Repositories\Deliverystatus\DeliverystatusRepositoryInterface  $deliverystatuses
	 * @return void
	 */
	public function __construct(DeliverystatusRepositoryInterface $deliverystatuses)
	{
		parent::__construct();

		$this->deliverystatuses = $deliverystatuses;
	}

	/**
	 * Display a listing of deliverystatus.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::deliverystatuses.index');
	}

	/**
	 * Datasource for the deliverystatus Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->deliverystatuses->grid();

		$columns = [
			'id',
			'code',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.orders.deliverystatuses.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new deliverystatus.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new deliverystatus.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating deliverystatus.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating deliverystatus.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified deliverystatus.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->deliverystatuses->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::deliverystatuses/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.deliverystatuses.all');
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
				$this->deliverystatuses->{$action}($row);
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
		// Do we have a deliverystatus identifier?
		if (isset($id))
		{
			if ( ! $deliverystatus = $this->deliverystatuses->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::deliverystatuses/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.deliverystatuses.all');
			}
		}
		else
		{
			$deliverystatus = $this->deliverystatuses->createModel();
		}

		// Show the page
		return view('sanatorium/orders::deliverystatuses.form', compact('mode', 'deliverystatus'));
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
		// Store the deliverystatus
		list($messages) = $this->deliverystatuses->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::deliverystatuses/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.deliverystatuses.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
