<?php namespace Sanatorium\Orders\Repositories;

class PaymentServiceRepository
{
    protected $services;

    public function registerService($service = null)
    {
        $this->services[$service] = $service;
    }

    public function getServices($position = null)
    {
        return $this->services;
    }
}
