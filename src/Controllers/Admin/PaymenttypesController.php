<?php namespace Sanatorium\Orders\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Paymenttype\PaymenttypeRepositoryInterface;

class PaymenttypesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Paymenttype\PaymenttypeRepositoryInterface
	 */
	protected $paymenttypes;

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
	 * @param  \Sanatorium\Orders\Repositories\Paymenttype\PaymenttypeRepositoryInterface  $paymenttypes
	 * @return void
	 */
	public function __construct(PaymenttypeRepositoryInterface $paymenttypes)
	{
		parent::__construct();

		$this->paymenttypes = $paymenttypes;
	}

	/**
	 * Display a listing of paymenttype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::paymenttypes.index');
	}

	/**
	 * Datasource for the paymenttype Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->paymenttypes->grid();

		$columns = [
			'id',
			'code',
			'money_min',
			'money_max',
			'payment_service',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.orders.paymenttypes.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new paymenttype.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new paymenttype.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating paymenttype.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating paymenttype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified paymenttype.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->paymenttypes->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::paymenttypes/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.paymenttypes.all');
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
				$this->paymenttypes->{$action}($row);
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
		// Do we have a paymenttype identifier?
		if (isset($id))
		{
			if ( ! $paymenttype = $this->paymenttypes->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::paymenttypes/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.paymenttypes.all');
			}
		}
		else
		{
			$paymenttype = $this->paymenttypes->createModel();
		}

		$services = app('sanatorium.orders.payment.services')->getServices();

		// Show the page
		return view('sanatorium/orders::paymenttypes.form', compact('mode', 'paymenttype', 'services'));
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
		// Store the paymenttype
		list($messages) = $this->paymenttypes->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::paymenttypes/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.paymenttypes.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
