<?php


namespace Discount\V1\Modules\Calculator\Data;


use App\Generics\SingleDataObject;

class CustomerData extends SingleDataObject
{
    /**
     * Return the customer revenue
     * @return string
     */
    public function revenue(): string
    {
        return $this->attributes['revenue'];
    }

    /**
     * Return the customer name
     * @return string
     */
    public function name(): string
    {
        return $this->attributes['name'];
    }
}