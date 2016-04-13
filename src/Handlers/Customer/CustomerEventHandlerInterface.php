<?php namespace Sanatorium\Orders\Handlers\Customer;

use Sanatorium\Orders\Models\Customer;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface CustomerEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a customer is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a customer is created.
	 *
	 * @param  \Sanatorium\Orders\Models\Customer  $customer
	 * @return mixed
	 */
	public function created(Customer $customer);

	/**
	 * When a customer is being updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Customer  $customer
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Customer $customer, array $data);

	/**
	 * When a customer is updated.
	 *
	 * @param  \Sanatorium\Orders\Models\Customer  $customer
	 * @return mixed
	 */
	public function updated(Customer $customer);

	/**
	 * When a customer is deleted.
	 *
	 * @param  \Sanatorium\Orders\Models\Customer  $customer
	 * @return mixed
	 */
	public function deleted(Customer $customer);

}
