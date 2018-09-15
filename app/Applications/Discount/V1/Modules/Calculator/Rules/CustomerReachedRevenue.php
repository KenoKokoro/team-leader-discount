<?php


namespace Discount\V1\Modules\Calculator\Rules;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

class CustomerReachedRevenue extends BaseRule
{
    const REVENUE_LIMIT = 1000;
    const DISCOUNT = 0.1;

    public function match(OrderData $data): bool
    {
        if ($data->customer()->revenue() > self::REVENUE_LIMIT) {
            $this->hasMatch = true;
        }

        return $this->hasMatch;
    }

    public function discount(OrderData $data): DiscountData
    {
        $attributes = $this->buildDiscountAttributes($data);

        return $this->data($attributes);
    }

    /**
     * Generate the discount attributes and prepare the attributes for response
     * @param OrderData $data
     * @return array
     */
    private function buildDiscountAttributes(OrderData $data): array
    {
        $total = floatval($data->total());

        return [
            'discount' => [
                'percent' => self::DISCOUNT * 100 . '%',
                'price' => $this->discountPrice($total, self::DISCOUNT),
                'difference' => $this->discountPriceDifference($total, self::DISCOUNT),
                'reason' => "The customer {$data->customer()->name()} has already spent over " . self::REVENUE_LIMIT . " € ({$data->customer()->revenue()} €)"
            ],
            'order' => $data->toArray()
        ];
    }

    /**
     * Used to calculate the new discount price
     * @param float $total
     * @param float $discount
     * @return string
     */
    private function discountPrice(float $total, float $discount): string
    {
        if ($this->hasMatch === false) {
            return $total;
        }

        return number_format(($total - floatval($this->discountPriceDifference($total, $discount))), 2);
    }

    /**
     * The difference how much the total price will be discounted
     * @param float $total
     * @param float $discount
     * @return string
     */
    private function discountPriceDifference(float $total, float $discount): string
    {
        return $total * $discount;
    }
}