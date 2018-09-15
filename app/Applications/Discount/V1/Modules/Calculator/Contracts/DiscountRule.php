<?php


namespace Discount\V1\Modules\Calculator\Contracts;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

interface DiscountRule
{
    /**
     * Check if the given rule matches against the given order data
     * @param OrderData $data
     * @return bool
     */
    public function match(OrderData $data): bool;

    /**
     * Get the discount for the given rule
     * @param OrderData $data
     * @return DiscountData
     */
    public function discount(OrderData $data): DiscountData;
}