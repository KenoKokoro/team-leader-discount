<?php


namespace Discount\V1\Modules\Calculator\Contracts;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;

interface DiscountCalculation
{
    /**
     * @param OrderData $order
     * @return DiscountData
     * @throws NotEligibleForDiscount
     */
    public function calculate(OrderData $order): DiscountData;
}