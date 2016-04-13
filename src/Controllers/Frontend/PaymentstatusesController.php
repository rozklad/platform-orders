<?php namespace Sanatorium\Orders\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class PaymentstatusesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/orders::index');
	}

}
