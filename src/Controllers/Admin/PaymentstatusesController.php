<?php namespace Sanatorium\Orders\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Paymentstatus\PaymentstatusRepositoryInterface;

class PaymentstatusesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Paymentstatus\PaymentstatusRepositoryInterface
	 */
	protected $paymentstatuses;

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
	 * @param  \Sanatorium\Orders\Repositories\Paymentstatus\PaymentstatusRepositoryInterface  $paymentstatuses
	 * @return void
	 */
	public function __construct(PaymentstatusRepositoryInterface $paymentstatuses)
	{
		parent::__construct();

		$this->paymentstatuses = $paymentstatuses;
	}

	/**
	 * Display a listing of paymentstatus.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::paymentstatuses.index');
	}

	/**
	 * Datasource for the paymentstatus Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->paymentstatuses->grid();

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
			$element->edit_uri = route('admin.sanatorium.orders.paymentstatuses.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new paymentstatus.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new paymentstatus.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating paymentstatus.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating paymentstatus.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified paymentstatus.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->paymentstatuses->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::paymentstatuses/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.paymentstatuses.all');
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
				$this->paymentstatuses->{$action}($row);
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
		// Do we have a paymentstatus identifier?
		if (isset($id))
		{
			if ( ! $paymentstatus = $this->paymentstatuses->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::paymentstatuses/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.paymentstatuses.all');
			}
		}
		else
		{
			$paymentstatus = $this->paymentstatuses->createModel();
		}

		// Show the page
		return view('sanatorium/orders::paymentstatuses.form', compact('mode', 'paymentstatus'));
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
		// Store the paymentstatus
		list($messages) = $this->paymentstatuses->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::paymentstatuses/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.paymentstatuses.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
