<?php


namespace App\Generics;


use Illuminate\Contracts\Support\Arrayable;

class SingleDataObject implements Arrayable
{
    protected $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get value from the attributes
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Dynamically set values to the attributes
     * @param string $key
     * @param        $value
     * @return SingleDataObject
     */
    public function __set(string $key, $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}