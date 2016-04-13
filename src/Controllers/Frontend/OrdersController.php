<?php namespace Sanatorium\Orders\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sentinel;

class OrdersController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::index');
	}

	public function show($id = null)
	{
		$orders = app('sanatorium.orders.order');

		$order = $orders->find($id);

		$user = Sentinel::getUser();

		// If it's not user's own order or if the user is not superuser 
		if ( $order->user_id != $user->id && !Sentinel::hasAnyAccess(['superuser']) )
			return redirect()->back();

		return view('sanatorium/orders::show', compact('order'));
	}

	public function track($id = null)
	{
		$orders = app('sanatorium.orders.order');

		$order = $orders->find($id);

		$user = Sentinel::getUser();

		// If it's not user's own order or if the user is not superuser 
		if ( $order->user_id != $user->id && !Sentinel::hasAnyAccess(['superuser']) )
			return redirect()->back();

		return view('sanatorium/orders::track', compact('order'));
	}

	public function slip($id = null)
	{
		$orders = app('sanatorium.orders.order');

		$order = $orders->find($id);

		$user = Sentinel::getUser();

		// If it's not user's own order or if the user is not superuser 
		if ( $order->user_id != $user->id && !Sentinel::hasAnyAccess(['superuser']) )
			return redirect()->back();

		return view('sanatorium/orders::slip', compact('order'));
	}

}
