<?php namespace Sanatorium\Orders\Widgets;

use Cart;
use Asset;
use Sanatorium\Shoppricing\Models\Currency;

class Hooks {

	/**
	 * Cart widget
	 */
	public function cart()
	{
		extract($this->getCartData());

		return view('sanatorium/orders::hooks/cart', compact('cart', 'items', 'quantity'));
	}

	/**
	 * Buy form
	 * @param  mixed $object Object (product) to be bought
	 */
	public function buy($object = null)
	{
		return view('sanatorium/orders::hooks/buy', compact('object'));
	}

	/**
	 * Asynchronous cart always present on the page.
	 * Show/hide on trigger.
	 */
	public function async()
	{
		extract($this->getCartData());

		return view('sanatorium/orders::hooks/async', compact('cart', 'items', 'quantity', 'subtotal', 'total', 'currency'));
	}

	/**
	 * Return cart data
	 * @return array Returns ['cart', 'items', 'quantity']
	 */
	public function getCartData()
	{
		return [
			'cart' 		=> Cart::getInstance(),
			'items' 	=> Cart::items(),
			'quantity' 	=> Cart::quantity(),
			'subtotal' 	=> Cart::subtotal(),
			'total'		=> Cart::total(),
			'currency'  => Currency::getActiveCurrency()
		];
	}

}
