<?php namespace Sanatorium\Orders\Repositories;

class PaymentServiceRepository
{
    protected $services;

    public function registerService($service = null)
    {
        //$class = str_replace("\\", "\\\\", get_class($service));
        $this->services[$service] = $service;
    }

    public function getServices($position = null)
    {
        return $this->services;
    }
}
