<?php


namespace App\External\Client\Exceptions;


use Exception;

class ServiceNotFound extends Exception
{
    public function __construct(string $service)
    {
        parent::__construct("Service {$service} couldn't not be found");
    }
}