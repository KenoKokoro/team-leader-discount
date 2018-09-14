<?php


namespace Discount\V1\Modules\Calculator;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

interface DiscountCalculation
{
    /**
     * @param OrderData $items
     * @return DiscountData
     */
    public function calculate(OrderData $items): DiscountData;
}