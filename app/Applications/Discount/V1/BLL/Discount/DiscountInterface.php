<?php


namespace Discount\V1\BLL\Discount;


use Illuminate\Contracts\Support\Arrayable;

interface DiscountInterface
{
    /**
     * Retrieve the validation rules that will confirm that we have all the required values in order to proceed with the calculation
     */
    public function calculationRules(): array;

    /**
     * Calculate the discount for the given order
     * @param array $order
     * @return Arrayable
     */
    public function calculateFor(array $order): Arrayable;
}