<?php namespace Sanatorium\Orders\Controllers\Admin;

use Event;
use Platform\Access\Controllers\AdminController;
use Sanatorium\Orders\Repositories\Order\OrderRepositoryInterface;
use Status;

class OrdersController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Orders repository.
	 *
	 * @var \Sanatorium\Orders\Repositories\Order\OrderRepositoryInterface
	 */
	protected $orders;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
		'customer_repair',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Orders\Repositories\Order\OrderRepositoryInterface  $orders
	 * @return void
	 */
	public function __construct(OrderRepositoryInterface $orders)
	{
		parent::__construct();

		$this->orders = $orders;
	}

	/**
	 * Display a listing of order.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$statuses = Status::where('status_entity', $this->orders->getModel())->get();

		return view('sanatorium/orders::orders.index', compact('statuses'));
	}

	/**
	 * Datasource for the order Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->orders->grid()->with('statuses')->with('deliverytype')->with('paymenttype');

		if ( request()->has('custom_filters') ) {

			$custom_filters = request()->get('custom_filters');

			$processed_filters = [];

			foreach ( $custom_filters as $filter ) {

				list($key, $compare, $value, $attribute_id) = explode(':', $filter);

				if ( !isset($processed_filters[$key]) ) {
					$processed_filters[$key] = [];
				}

				$processed_filters[$key][] = [
					'key' => $key,
					'value' => $value,
					'compare' => $compare,
					'attribute_id' => $attribute_id
				];

			}

			foreach( $processed_filters as $filters ) {

				$data->where(function($query) use ($filters) {

					$index_filter = 0;

					foreach( $filters as $filter ) {

						$index_filter++;

						extract($filter);

						if ( $index_filter == 1 ) {
							$wherehas_method = 'whereHas';
							$where_method = 'where';
						} else {
							$wherehas_method = 'orWhereHas';
							$where_method = 'orWhere';
						}

						if ( strpos($key, '..') === false ) {

							$query->{$where_method}($key, $compare, $value);

						} else {

							$key = str_replace('..', '', $key);

							$query->{$wherehas_method}($key, function($q) use ($compare, $value, $attribute_id) {
								$q->where($attribute_id, $compare, $value);
							});

						}

					}

				});

			}

		}

		$columns = [
			'id',
			'user_id',
			'payment_id',
			'delivery_id',
			'payment_type_id',
			'delivery_type_id',
			'address_delivery_id',
			'address_billing_id',
			'customer_id',
			'created_at'
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.orders.orders.edit', $element->id);

			$element->status = $element->status;

			$element->deliverytype = $element->deliverytype;

			$element->paymenttype = $element->paymenttype;

			$element->deliveryaddress = $element->deliveryaddress()->first();

			$element->customer_uri = route('admin.sanatorium.orders.customers.edit', $element->customer_id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new order.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new order.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating order.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified order.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->orders->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/orders::orders/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.orders.orders.all');
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
				$this->orders->{$action}($row);
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
		// Do we have a order identifier?
		if (isset($id))
		{
			if ( ! $order = $this->orders->find($id))
			{
				$this->alerts->error(trans('sanatorium/orders::orders/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.orders.orders.all');
			}
		}
		else
		{
			$order = $this->orders->createModel();
		}

		// Find out what given payment service supports
		$supports = [];

		$supportable = [
			'close',
			'refund',
			'reverse',
		];

		// Get payment service
		$payment_service = $order->payment_service;

		if ( class_exists($payment_service) && is_object($order->payment) && $order->payment->provider_id ) {

			$payment_service = new $payment_service;

			foreach( $supportable as $supportable_method ) {

				if ( method_exists($payment_service, $supportable_method) ) {

					$supports[] = $supportable_method;

				}

			}

		}

		// Show the page
		return view('sanatorium/orders::orders.form', compact('mode', 'order', 'supports'));
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
		// Store the order
		list($messages) = $this->orders->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/orders::orders/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.orders.orders.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

	public function status($order_id = null, $status = null)
	{
		if ( !$order_id )
			$order_id = request()->get('order_id');
		
		if ( !$status )
			$status = request()->get('status');

		$order = $this->orders->find($order_id);

		if ( !$order )
			return ['found' => false, 'success' => false];

		$order->statuses()->attach($status);
		
		Event::fire('sanatorium.orders.status.changed.' . $status . '.' . $order->deliverytype->code, [$order]);
		
		return [
				'order' => $order,
				'event' => 'sanatorium.orders.status.changed.' . $status . '.' . $order->deliverytype->code
			]; 
	}

	public function tracking($order_id = null, $tracking_number = null)
	{
		if ( !$order_id )
			$order_id = request()->get('order_id');
		
		if ( !$tracking_number )
			$tracking_number = request()->get('tracking_number');

		$order = $this->orders->find($order_id);

		if ( !$order )
			return ['found' => false, 'success' => false];

		$order->tracking_number = $tracking_number;

		$order->save();
		
		Event::fire('sanatorium.orders.tracking_number.changed', [$order]);
		
		return [
				'order' => $order,
				'event' => 'sanatorium.orders.tracking_number.changed'
			]; 
	}

	public function send($order_id = null)
	{
		if ( !$order_id )
			$order_id = request()->get('order_id');

		$order = $this->orders->find($order_id);
		
		if ( !$order )
			return ['found' => false, 'success' => false];
		
		Event::fire('sanatorium.orders.order.placed', [$order]);

		return [
			'order' => $order,
			'event' => 'sanatorium.orders.order.placed'
		];
	}

	public function forgot($order_id = null)
	{
		if ( !$order_id )
			$order_id = request()->get('order_id');

		$order = $this->orders->find($order_id);
		
		if ( !$order )
			return ['found' => false, 'success' => false];
		
		Event::fire('sanatorium.orders.order.forgot', [$order]);

		return [
			'order' => $order,
			'event' => 'sanatorium.orders.order.forgot'
		];
	}

	public function action()
	{
		$args = request()->all();
		
		extract($args);

		$order = $this->orders->find($order_id);

		// Get payment service
		$payment_service = $order->payment_service;

		if ( class_exists($payment_service) && is_object($order->payment) && $order->payment->provider_id ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, $action) ) {

				$result = $payment_service->{$action}($order, $args);

				if ( $result === true ) {

					return [
						'type' => 'success',
						'msg' => trans('sanatorium/orders::orders/message.success.'.$action),
					];

				} else if ( is_array($result) ) {

					if ( $result['success'] ) {

						return [
							'type' => 'success',
							'msg' => trans('sanatorium/orders::orders/message.success.'.$action),
						];

					} else {

						return [
							'type' => 'danger',
							'msg' => $result['msg'],
						];

					}

				}

			}

		}

		return [
			'type' => 'danger',
			'msg' => trans('sanatorium/orders::orders/message.actions.error.'.$action)
		];
	}

	public function exportCpost($id)
	{
		$order = $this->orders->find($id);

		$delivery = \Sanatorium\Addresses\Models\Address::find($order->address_delivery_id);

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=order".$id.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		//Ronkov�	Jolana	Kv�tnov�ho v�t�zstv�	772	Praha 4	14900	CZ	1827	CZK	16000936	777347883	a400@seznam.cz	1827	DR	16000936	1.03

		$name = explode(" ", $delivery->name);

		$firstname = '';
		if ( isset($name[0]) ) 
			$firstname = $name[0];

		$lastname = '';
		if ( isset($name[1]) )
			$lastname = $name[1];

		echo "{$lastname};{$firstname};{$delivery->street};{$delivery->city};{$delivery->postcode};{$delivery->country};{$order->price_vat};CZK;{$order->id};{$order->order_phone};{$order->order_email};DR;{$order->id};0.00\n";
	}

	public function exportGls($id)
	{
		$order = $this->orders->find($id);

		$delivery = \Sanatorium\Addresses\Models\Address::find($order->address_delivery_id);

		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=order".$id.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		$name = explode(" ", $delivery->name);

		$firstname = '';
		if ( isset($name[0]) ) 
			$firstname = $name[0];

		$lastname = '';
		if ( isset($name[1]) )
			$lastname = $name[1];

		echo "{$lastname};{$firstname};{$delivery->street};{$delivery->city};{$delivery->postcode};{$delivery->country};{$order->price_vat};CZK;{$order->id};{$order->order_phone};{$order->order_email};DR;{$order->id};0.00\n";
	}

}
