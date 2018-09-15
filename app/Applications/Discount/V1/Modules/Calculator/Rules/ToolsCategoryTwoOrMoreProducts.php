<?php


namespace Discount\V1\Modules\Calculator\Rules;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

class ToolsCategoryTwoOrMoreProducts extends BaseRule
{
    public function match(OrderData $data): bool
    {
        // TODO: Implement match() method.
    }

    public function discount(OrderData $data): DiscountData
    {
        // TODO: Implement discount() method.
    }
}