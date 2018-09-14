<?php


namespace Discount\V1\BLL\Discount;


use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\DiscountCalculation;
use Illuminate\Contracts\Support\Arrayable;

class DiscountLayer implements DiscountInterface
{
    /**
     * @var DiscountCalculation
     */
    private $calculation;

    public function __construct(DiscountCalculation $calculation)
    {
        $this->calculation = $calculation;
    }

    public function calculationRules(): array
    {
        return [
            'customer-id' => 'required',
            'total' => 'required',
            'items' => 'required|array',
            'items.*.product-id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.unit-price' => 'required',
            'items.*.total' => 'required',
        ];
    }

    public function calculateFor(array $products): Arrayable
    {
        return $this->calculation->calculate(new OrderData($products));
    }
}