<?php


namespace Discount\V1\Modules\Calculator\Contracts;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;

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
     * @param OrderData $order
     * @return DiscountData
     * @throws NotEligibleForDiscount
     */
    public function discount(OrderData $order): DiscountData;
}