<?php


namespace App\Generics;


use Illuminate\Contracts\Support\Arrayable;

interface GenericFactoryInterface
{
    /**
     * Factory to create instances that inherit the generics
     * @param string $className
     * @param array  $attributes
     * @return Arrayable
     */
    public function make(string $className, array $attributes): Arrayable;
}