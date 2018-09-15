<?php


namespace App\Generics;


use Illuminate\Contracts\Support\Arrayable;

class GenericFactory implements GenericFactoryInterface
{
    public function make(string $className, array $attributes): Arrayable
    {
        return new $className($attributes);
    }
}