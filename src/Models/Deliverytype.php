<?php namespace Sanatorium\Orders\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Sanatorium\Pricing\Traits\PriceableTrait;

class Deliverytype extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait, PriceableTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'shop_delivery_types';

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
	protected static $entityNamespace = 'sanatorium/orders.deliverytype';

	public function paymenttypes()
	{
		return $this->belongsToMany('Sanatorium\Orders\Models\PaymentType', 'shop_deliverytypes_paymenttypes', 'delivery_type_id', 'payment_type_id');
	}

	public function setPaymenttypesAttribute($value = [])
	{
		$this->paymenttypes()->sync($value);
	}

}
