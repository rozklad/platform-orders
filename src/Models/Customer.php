<?php namespace Sanatorium\Orders\Models;

use Cartalyst\Attributes\EntityInterface;
use Illuminate\Database\Eloquent\Model;
use Platform\Attributes\Traits\EntityTrait;
use Cartalyst\Support\Traits\NamespacedEntityTrait;
use Platform\Users\Models\User;

class Customer extends User {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'customers';


	/**
	 * {@inheritDoc}
	 */
	protected static $entityNamespace = 'sanatorium/orders.customer';

}
