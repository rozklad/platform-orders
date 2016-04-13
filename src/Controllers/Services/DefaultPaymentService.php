<?php namespace Sanatorium\Orders\Controllers\Services;

class DefaultPaymentService {

	public $name;
	public $description;

	public function __construct()
	{
		$this->name = trans('sanatorium/orders::payment_services.default.name');
		$this->description = trans('sanatorium/orders::payment_services.default.description');
	}

}