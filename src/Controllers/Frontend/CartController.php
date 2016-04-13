<?php namespace Sanatorium\Orders\Controllers\Frontend;

use Cart;
use Cartalyst\Conditions\Condition;
use Converter;
use Cookie;
use Event;
use Platform\Foundation\Controllers\Controller;
use Product;
use Sentinel;
use Sanatorium\Shoppricing\Models\Currency;
use Sanatorium\Orders\Models\Customer;
use Sanatorium\Orders\Repositories\Order\OrderRepositoryInterface;
use Sanatorium\Orders\Repositories\Deliverytype\DeliverytypeRepositoryInterface;
use Sanatorium\Orders\Repositories\Paymenttype\PaymenttypeRepositoryInterface;
use Sanatorium\Orders\Repositories\Customer\CustomerRepositoryInterface;
use Sanatorium\Addresses\Repositories\Countries\CountriesRepositoryInterface;

class CartController extends Controller {

	public function __construct(OrderRepositoryInterface $orders,
		DeliverytypeRepositoryInterface $deliverytypes,
		PaymenttypeRepositoryInterface $paymenttypes,
		CustomerRepositoryInterface $customers,
		CountriesRepositoryInterface $countries)
	{

		parent::__construct();

		$this->orders = $orders;

		$this->deliverytypes = $deliverytypes;

		$this->paymenttypes = $paymenttypes;

		$this->customers = $customers;

		$this->countries = $countries;
		
	}

	public function add()
	{
		$product = Product::find( request()->get('id') );

		$item = [
			'id' 		=> $product->id,
			'quantity' 	=> (int)request()->get('quantity'),
			'name' 		=> $product->product_title,
			'price' 	=> $product->getPrice('vat', 1, null, false),
			'weight' 	=> $product->weight
		];

		Cart::add($item);

		if ( !request()->ajax() ) {
			return redirect()->back();
		}

		return redirect()->back();
	}

	public function delete()
	{
		if ( !$rowid = request()->get('rowid') )
			return response('Failed', 500);

		try {
			Cart::remove($rowid);
		} catch(\Cartalyst\Cart\Exceptions\CartItemNotFoundException $e) {
			// 
			$object = [
				'level' => '601',
				'level_name' => 'cart',
				'datetime' => \Carbon\Carbon::now(),
				'message' => 'Cart::remove('.$rowId.') failed for ' . $_SERVER['REMOTE_ADDR']
			]; 
			Event::fire('logger.error', [ $record ]);
		}

		return $this->prices();
	}

	public function update()
	{
		if ( request()->has('delivery_id') )
			return $this->setDelivery( request()->get('delivery_id') );

		if ( request()->has('payment_id') )
			return $this->setPayment( request()->get('payment_id') );

		if ( !$rowid = request()->get('rowid') )
			return response('Failed', 500);

		Cart::update($rowid, (int)request()->get('quantity'));

		return $this->prices();
	}

	public function getPrimaryAddresses()
	{
		$this->addresses = app('Sanatorium\Addresses\Repositories\Address\AddressRepositoryInterface');

		if ( Sentinel::check() ) {

        	$user = Sentinel::getUser();

        	$addresses = $this->addresses->where('user_id', $user->id)->get();

    	} else {

    		$addresses = [];

    	}

        $primaryAddresses = [];

        foreach( $addresses as $address ) {

            $primaryAddresses[$address->type] = $address;

        }

        if ( !isset($primaryAddresses['fakturacni']) ) {

            $primaryAddresses['fakturacni'] = new \Sanatorium\Addresses\Models\Address;

        }

        if ( !isset($primaryAddresses['dodaci']) ) {

            $primaryAddresses['dodaci'] = new \Sanatorium\Addresses\Models\Address;
            
        }

        return $primaryAddresses;
	}

	public function index()
	{
		$activeSummary = true;
		$compatibleQuantity = true;
		$items = Cart::items();
		$prices = $this->prices();
		$currency = Currency::getPrimaryCurrency();

		if ( $user = Sentinel::getUser() ) {
			$customer = Customer::find($user->id);
		} else {
			$customer = new Customer;
		}

		$primaryAddresses = $this->getPrimaryAddresses();

		// Shopping only for logged in users
		if ( config('sanatorium-orders.only_logged_in') && !Sentinel::check() )
			return redirect()->to('/login');

		switch( config('sanatorium-orders.cart_mode') ) 
		{
			case 'single':
				return view('sanatorium/orders::cart/single/index', compact(
					'activeSummary', 
					'compatibleQuantity', 
					'items', 
					'customer',
					'prices',
					'currency',
					'primaryAddresses'
					) );
			break;

			case 'steps':
				return view('sanatorium/orders::cart/steps/index', compact(
					'activeSummary', 
					'compatibleQuantity', 
					'items', 
					'customer',
					'prices',
					'currency',
					'primaryAddresses'
					) );
			break;
		}
	}

	public function delivery()
	{
		$items = Cart::items();

		$prices = $this->prices();

		$addresses = $this->processAddresses();

		// Shopping only for logged in users
		if ( config('sanatorium-orders.only_logged_in') && !Sentinel::check() )
			return redirect()->to('/login');

		if ( !isset($addresses['fakturacni']) )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['dodaci']) )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['fakturacni']['name']) || $addresses['fakturacni']['name'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['dodaci']['name']) || $addresses['dodaci']['name'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['fakturacni']['street']) || $addresses['fakturacni']['street'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['dodaci']['street']) || $addresses['dodaci']['street'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['fakturacni']['city']) || $addresses['fakturacni']['city'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['dodaci']['city']) || $addresses['dodaci']['city'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['fakturacni']['country']) || $addresses['fakturacni']['country'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		if ( !isset($addresses['dodaci']['country']) || $addresses['dodaci']['country'] == '' )
			return redirect()->back()->withErrors(['Please provide required address information']);

		// These inputs will be handed over to next steps
		$handover = request()->all();

		// Shipping to region outside of Europe
		$shipping_multiplier = 1;

		$country = null;

		if ( request()->has('address') ) {
			$delivery = request()->get('address')['dodaci'];

			if ( isset($delivery['country']) ) {

				$country = $this->countries->where('name_simple', $delivery['country'])->first();
				/*
				if ( is_object($country) ) {

					if ( $country->region !== 'Europe' && $country->subregion !== 'Northern America' ) {

						$handover['shipping_multiplier'] = 1.5;
						$shipping_multiplier = 1.5;
						Cart::setMetaData('shipping_multiplier', 1.5);
					}

				}*/

			}
		}
		Cart::setMetaData('shipping_multiplier', 1);

		return view('sanatorium/orders::cart/steps/delivery', compact('items', 'prices', 'handover', 'shipping_multiplier', 'country'));
	}

	public function user()
	{
		$items = Cart::items();

		$prices = $this->prices();

		$primaryAddresses = $this->getPrimaryAddresses();

		$deliveryCountries = $this->countries->where('delivering', 1)->get();

		$suggestedCountries = [
			'dodaci' => $primaryAddresses['dodaci']->country ? $primaryAddresses['dodaci']->country : $this->suggestCountry(),
			'fakturacni' => $primaryAddresses['fakturacni']->country ? $primaryAddresses['fakturacni']->country : $this->suggestCountry()
		];

		// These inputs will be handed over to next steps
		$handover = request()->all();

		// Shopping only for logged in users
		if ( config('sanatorium-orders.only_logged_in') && !Sentinel::check() )
			return redirect()->to('/login');

		return view('sanatorium/orders::cart/steps/user', compact(
			'items', 
			'prices', 
			'primaryAddresses', 
			'deliveryCountries',
			'suggestedCountries',
			'handover'
		));
	}

	public static function simpleItems()
	{
		$items = Cart::items();
		$results = [];

		foreach( $items as $item ) {
			$product = Product::find($item->get('id'));

			if ( is_object($product) ) {
				$row = [
					'rowid' => $item->get('rowId'),
					'vat_quantity' => $product->getPrice('vat', $item->get('quantity')),
					'plain_quantity' => $product->getPrice('plain', $item->get('quantity'))
				];

				$results[$row['rowid']] = $row;
			}
		}

		return $results;
	}

	public function prices()
	{
		return [
			'items' 	=> self::simpleItems(),
			//'total' 	=> Product::formatGeneric(Cart::total()),
			'weight' 	=> Cart::weight(),
			'subtotal'  => Cart::subtotal(),
			'total' 	=> Cart::total() + $this->deliveryPrice(),
			'totals' 	=> $this->totals(),
			'count'		=> Cart::count()
		];
	}

	public function totals()
	{
		$currency = Currency::getPrimaryCurrency();

		return [
			'products-price'	=> Cart::total() < 0 ? Converter::to('currency.'.$currency->code)->value(0)->format($currency->short_format) : Converter::to('currency.'.$currency->code)->value(Cart::total())->format($currency->short_format),
			'delivery-price'	=> Cart::total() < 0 ? Converter::to('currency.'.$currency->code)->value(0)->format($currency->short_format) : Converter::to('currency.'.$currency->code)->value($this->deliveryPrice())->format($currency->short_format),
			'total-price'		=> Cart::total() < 0 ? Converter::to('currency.'.$currency->code)->value(0)->format($currency->short_format) : Converter::to('currency.'.$currency->code)->value(Cart::total() + $this->deliveryPrice())->format($currency->short_format)
		];
	}

	public function clear()
	{
		// Clean up products
		Cart::clear();

		// Remove conditions - shipping, tax, other
		Cart::removeConditions();

		if ( request()->ajax() )
			return response('Success');

		return redirect()->back()->withCookie(Cookie::forget('current_order_id'));
	}

	public function setDelivery($delivery_id = null)
	{
		if ( !is_numeric($delivery_id) )
			return response('Failed', 500);

		$deliverytype = $this->deliverytypes->find($delivery_id);

		Cart::setMetaData('deliverytype', $deliverytype);

		if ( request()->ajax() )
			return $this->prices();
	}

	public function getDelivery()
	{
		return Cart::getMetaData('deliverytype');
	}

	public function setPayment($payment_id = null)
	{
		if ( !is_numeric($payment_id) )
			return response('Failed', 500);

		$paymenttype = $this->paymenttypes->find($payment_id);

		Cart::setMetaData('paymenttype', $paymenttype);

		if ( request()->ajax() )
			return response('Success');
	}

	public function getPayment()
	{
		return Cart::getMetaData('paymenttype');
	}

	public function deliveryPrice()
	{
		if ( !$deliverytype = Cart::getMetaData('deliverytype') ) {
			return 0;
		}

		if ( !Cart::count() ) {
			return 0;
		}

		$shipping_multiplier = 1;

		if ( Cart::getMetaData('shipping_multiplier') )
			$shipping_multiplier = Cart::getMetaData('shipping_multiplier');

		return $deliverytype->getPrice('vat', 1, null, false) * $shipping_multiplier;
	}

	public function place()
	{
		$cart_mode = config('sanatorium-orders.cart_mode');

		// Shopping only for logged in users
		if ( config('sanatorium-orders.only_logged_in') && !Sentinel::check() )
			return redirect()->to('/login');

		$addresses = $this->processAddresses();

		// Was delivery & payment fast forwarded?
		if ( config('sanatorium-orders.ff_delivery_payment') ) {
			$delivery_type_id = $this->deliverytypes->first()->id;
			$payment_type_id = $this->paymenttypes->first()->id;
		} else {
			// Was delivery & payment set in cart meta?
			if ( $delivery = $this->getDelivery() )
				$delivery_type_id = $delivery->id;

			if ( $payment = $this->getPayment() )
				$payment_type_id = $payment->id;

			// Was delivery & payment set in input?
			if ( !isset($delivery_type_id) && request()->has('deliverytype') )
				$delivery_type_id = request()->get('deliverytype');

			if ( !isset($payment_type_id) && request()->has('paymenttype') )
				$payment_type_id = request()->get('paymenttype');
		}
		
		// Check if delivery & payment are chosen
		if ( !isset($delivery_type_id) )
			return redirect()->back()->withErrors(['Není vybrána doprava'])->withInput();

		if ( !isset($payment_type_id) )
			return redirect()->back()->withErrors(['Není vybrána platba'])->withInput();

		$deliverytype = $this->deliverytypes->find($delivery_type_id);
		
		if ( !isset($addresses['fakturacni']) )
			return redirect()->back();

		if ( !isset($addresses['dodaci']) )
			return redirect()->back();

		$order_data = [
			'delivery_type_id' 		=> $delivery_type_id,
			'payment_type_id'  		=> $payment_type_id,
			'address_billing_id' 	=> $addresses['fakturacni']->id,
			'address_delivery_id' 	=> $addresses['dodaci']->id,
			'cart'					=> Cart::serialize(),
			'order_email'			=> request()->get('order_email'),
			'currency_id'			=> Currency::getActiveCurrency()->id
		];

		// Identify user
		if ( $user = Sentinel::getUser() ) {
			$order_data['user_id'] = $user->id;
		}

		// Identify customer or create one
		if ( $customer = $this->customers->where('email', $order_data['order_email'])->first() ) {
			$order_data['customer_id'] = $customer->id;
		} else {
			list($messages, $customer) = $this->customers->create([
				'email' => $order_data['order_email'],
				'user_id' => isset($order_data['user_id']) ? $order_data['user_id'] : null
				]);
			$order_data['customer_id'] = $customer->id;
		}

		list($messages, $order) = $this->orders->create($order_data);

		// Add user id to customer, if available
		// and not currently logged in
		if ( !$customer->user_id ) {
			$this->users = app('platform.users');
			$user = $this->users->where('email', $order->order_email)->first();

			if ( $user ) {
				$customer->user_id = $user->id;
				$customer->save();
			}
		}

		// Remove shipping conditions
		Cart::removeConditions('other');

		// Condition shipping
		$condition = new Condition([
		    'name'   => 'Shipping',
		    'type'   => 'other',
		    'target' => 'subtotal',
		]);

		$shipping_multiplier = 1;

		if ( request()->has('shipping_multiplier') )
			$shipping_multiplier = request()->get('shipping_multiplier');

		$condition->setActions([

		    [
		        'value' => ceil((int)$deliverytype->price_vat * $shipping_multiplier),
		    ],

		]);

		Cart::condition($condition);

		// Store price
		$order->price_vat = Cart::total();
		$order->save();

		Cookie::queue('current_order_id', $order->id, 120);
		
		// Do we have any errors?
		if ( $messages->isEmpty() ) {

			switch ( $cart_mode ) {

				case 'single':

					// Confirmation step is not provided, 
					// therefore set confirmed status right now
					$order->statuses()->sync([ config('sanatorium-orders.default_order_status') ]);

					// Order was succesfully placed
					Cart::clear();
					
					return redirect()->route('sanatorium.orders.cart.placed');

				break;

				case 'steps':

					return redirect()->route('sanatorium.orders.cart.confirm');

				break;

			}

		} else {

			$this->alerts->error($messages, 'form');

			return redirect()->back()->withInput();
		}
	}

	public function placed()
	{
		$order = $this->getCurrentOrder();

		if ( !is_object($order) )
			return redirect()->to('/');
		
		// Order is already confirmed (status = default and mode = steps)
		if ( $order->status->id == config('sanatorium-orders.default_order_status') && config('sanatorium-orders.cart_mode') == 'steps' )
			return redirect()->to('/');

		// Clear cart
		Cart::clear();

		// Set status to default order status
		$order->statuses()->sync([ config('sanatorium-orders.default_order_status') ]);

		// Check if online payment was chosen
		$payment_online = false;
		$payment_success = false;

		$payment_service = $order->payment_service;

		if ( class_exists($payment_service) ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, 'isSuccess') ) {

				$payment_online = true;
				$payment_success = $payment_service->isSuccess($order);

			}

		}

		if ( $payment_online ) {
			if ( $payment_success ) {
				// Placed
				Event::fire('sanatorium.orders.order.placed', [ $order ]);
			} else {
				// Cancelled
				Event::fire('sanatorium.orders.order.cancelled', [ $order ]);
			}
		} else {
			// Placed
			Event::fire('sanatorium.orders.order.placed', [ $order ]);
		}

		$shipping_multiplier = 1;
		
		if ( request()->has('shipping_multiplier') )
			$shipping_multiplier = request()->get('shipping_multiplier');

		return view('sanatorium/orders::cart/actions/placed', compact('order', 'payment_online', 'payment_success', 'shipping_multiplier'));
	}

	public function confirm()
	{
		// Shopping only for logged in users
		if ( config('sanatorium-orders.only_logged_in') && !Sentinel::check() )
			return redirect()->to('/login');

		$items = Cart::items();

		$prices = $this->prices();

		$primaryAddresses = $this->getPrimaryAddresses();

		$order_id = Cookie::get('current_order_id');

		$order = $this->orders->find($order_id);

		$currency = Currency::getActiveCurrency();

		$shipping_multiplier = 1;
		
		if ( request()->has('shipping_multiplier') )
			$shipping_multiplier = request()->get('shipping_multiplier');

		return view('sanatorium/orders::cart/steps/confirm', compact(
			'items', 
			'prices', 
			'primaryAddresses',
			'order',
			'currency',
			'shipping_multiplier'
		));
	}

	/**
	 * User confirmed order, let's place the order
	 * or redirect the user to payment gate.
	 * @return [type] [description]
	 */
	public function confirmed()
	{
		$order = $this->getCurrentOrder();

		if ( !$order )
			return redirect()->back();

		// Attach payment to order
		$payments = app('sanatorium.orders.payment');

		list($messages, $payment) = $payments->create([
			'payment_type_id' => $order->payment_type_id
			]);

		$order->payment()->associate($payment);

		$order->save();

		// Order confirmed
		Event::fire('sanatorium.orders.order.confirmed', [ $order ]);

		$payment_service = $order->payment_service;

		if ( class_exists($payment_service) ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, 'process') ) {

				return $payment_service->process($order);

			}

		}

		return redirect()->route('sanatorium.orders.cart.placed');
	}

	public function getCurrentOrder()
	{
		$order_id = Cookie::get('current_order_id');

		$order = $this->orders->find($order_id);

		return $order;
	}

	public function processAddresses()
    {

        $addresses = request()->get('address');

        $this->addresses = app('Sanatorium\Addresses\Repositories\Address\AddressRepositoryInterface');

        $prepared_addresses = [];

        if ( is_array($addresses) ) {
	        foreach( $addresses as $type => $address ) {

	            if ( $type != 'firemni' ) {

	                $prepared_addresses[$type] = $address;
	                $prepared_addresses[$type]['type'] = $type;

	            }

	        }
	    }

        if ( isset($addresses['firemni']) && isset($addresses['fakturacni']) ) {

            $prepared_addresses['fakturacni']['ic'] = $addresses['firemni']['ic'];
            $prepared_addresses['fakturacni']['dic'] = $addresses['firemni']['dic'];

        }

        $addresses = [];

        foreach( $prepared_addresses as $type => $address ) {

            list($messages, $addresses[$type]) = $this->addresses->create($address);

        }

        return $addresses;

    }

    /**
	 * Helper function to suggest country where
	 * the user comes from.
	 * @return [type] [description]
	 */
	public function suggestCountry()
	{
		return \Sanatorium\Addresses\Widgets\Hooks::suggestCountry();
	}

	public function debug()
	{
		return [
			'items' 	=> self::simpleItems(),
			//'total' 	=> Product::formatGeneric(Cart::total()),
			'weight' 	=> Cart::weight(),
			'subtotal'  => Cart::subtotal(),
			'total' 	=> Cart::total() + $this->deliveryPrice(),
			'totals' 	=> $this->totals(),
			'count'		=> Cart::count(),
			'deliveryPrice'	
						=> $this->deliveryPrice(),
			'conditions'=> Cart::conditionsTotal(),
		];
	}
}
