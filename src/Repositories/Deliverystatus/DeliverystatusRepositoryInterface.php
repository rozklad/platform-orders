<?php namespace Sanatorium\Orders\Repositories\Deliverystatus;

interface DeliverystatusRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Orders\Models\Deliverystatus
	 */
	public function grid();

	/**
	 * Returns all the orders entries.
	 *
	 * @return \Sanatorium\Orders\Models\Deliverystatus
	 */
	public function findAll();

	/**
	 * Returns a orders entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Orders\Models\Deliverystatus
	 */
	public function find($id);

	/**
	 * Determines if the given orders is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given orders is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given orders.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a orders entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Orders\Models\Deliverystatus
	 */
	public function create(array $data);

	/**
	 * Updates the orders entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Orders\Models\Deliverystatus
	 */
	public function update($id, array $data);

	/**
	 * Deletes the orders entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
