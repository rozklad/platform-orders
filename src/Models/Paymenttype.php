<?php namespace Sanatorium\Orders\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;

class Paymenttype extends Model implements EntityInterface {

	use EntityTrait, NamespacedEntityTrait;

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'shop_payment_types';

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
	protected static $entityNamespace = 'sanatorium/orders.paymenttype';

	public function deliverytypes()
	{
		return $this->belongsToMany('Sanatorium\Orders\Models\DeliveryType', 'shop_deliverytypes_paymenttypes', 'payment_type_id', 'delivery_type_id');
	}

	public function setDeliverytypesAttribute($value = [])
	{
		$this->deliverytypes()->sync($value);
	}
}
