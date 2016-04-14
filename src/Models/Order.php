<?php namespace Sanatorium\Orders\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Sanatorium\Status\Traits\StatusableTrait;
use Sanatorium\Pricing\Traits\PriceableTrait;

class Order extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait, StatusableTrait, PriceableTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'shop_orders';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/orders.order';

	public function payment()
	{
		return $this->belongsTo('Sanatorium\Orders\Models\Payment', 'payment_id');
	}

	public function delivery()
	{
		return $this->belongsTo('Sanatorium\Orders\Models\Delivery', 'delivery_id');
	}

	public function paymenttype()
	{
		return $this->belongsTo('Sanatorium\Orders\Models\Paymenttype', 'payment_type_id');
	}

	public function deliverytype()
	{
		return $this->belongsTo('Sanatorium\Orders\Models\Deliverytype', 'delivery_type_id');
	}

	public function getPaymentServiceAttribute()
	{
		if ( is_object($this->paymenttype) )
			return $this->paymenttype->payment_service;
		else
			return \Sanatorium\Orders\Models\Paymenttype::find( config('sanatorium-orders.default_payment_type') );
	}

	public function deliveryaddress()
	{
		return $this->belongsTo('Sanatorium\Addresses\Models\Address', 'address_delivery_id');
	}

	public function billingaddress()
	{
		return $this->belongsTo('Sanatorium\Addresses\Models\Address', 'address_billing_id');
	}

	public function customer()
	{
		return $this->belongsTo('Sanatorium\Orders\Models\Customer', 'customer_id');
	}

	public function getPublicIdAttribute()
	{
		return str_pad($this->id, 9, '0', STR_PAD_LEFT);
	}

	public function getPaymentProviderStatusAttribute()
	{
		$action = 'status';

		if ( !isset($args) )
			$args = [];

		// Get payment service
		$payment_service = $this->payment_service;

		if ( class_exists($payment_service) && is_object($this->payment) && $this->payment->provider_id ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, $action) ) {

				return $payment_service->{$action}($this, $args);

			}

		}

		return null;
	}

	public function isPaymentOpened()
	{
		$action = 'isPaymentOpened';

		// Get payment service
		$payment_service = $this->payment_service;

		if ( class_exists($payment_service) && is_object($this->payment) && $this->payment->provider_id ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, $action) ) {

				return $payment_service->{$action}($this);

			}

		}

		return false;
	}

	public function getPaymentProviderStatusHumanReadableAttribute()
	{
		$action = 'status_human_readable';

		if ( !isset($args) )
			$args = [];

		// Get payment service
		$payment_service = $this->payment_service;

		if ( class_exists($payment_service) && is_object($this->payment) && $this->payment->provider_id ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, $action) ) {

				return $payment_service->{$action}($this, $args);

			}

		}

		return null;
	}

}
