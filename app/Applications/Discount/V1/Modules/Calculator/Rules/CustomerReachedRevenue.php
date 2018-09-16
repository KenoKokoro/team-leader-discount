<?php


namespace Discount\V1\Modules\Calculator\Rules;


use Discount\V1\Modules\Calculator\Data\CustomerData;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;

class CustomerReachedRevenue extends BaseRule
{
    const REVENUE_LIMIT = 1000;
    const DISCOUNT = 0.1;
    const KEY = 'CUSTOMER_PASSED_REVENUE_LIMIT';

    /**
     * @var CustomerData
     */
    private $customer;

    public function match(OrderData $data): bool
    {
        if ($data->customer()->revenue() > self::REVENUE_LIMIT) {
            $this->hasMatch = true;
            $this->customer = $data->customer();
        }

        return $this->hasMatch;
    }

    public function discount(OrderData $order): DiscountData
    {
        if (is_null($this->customer)) {
            throw new NotEligibleForDiscount($order);
        }

        $attributes = $this->buildDiscountAttributes($order);

        return $this->data($attributes);
    }

    protected function discountedUnitPrice(array $item): float
    {
        $price = floatval($item['unit-price']);

        return ($price - ($price * self::DISCOUNT));
    }

    protected function discountedItemTotal(array $item): float
    {
        $unit = floatval($item['unit-price']);

        return $item['quantity'] * $unit;
    }

    /**
     * Generate the discount attributes and prepare the attributes for response
     * @param OrderData $data
     * @return array
     */
    private function buildDiscountAttributes(OrderData $data): array
    {
        $total = floatval($data->total());
        $affectedIds = array_pluck($data->toArray()['items'], ['product-id']);

        return [
            'discount' => [
                'key' => self::KEY,
                'percent' => self::DISCOUNT * 100 . '%',
                'price' => $this->discountPrice($total, self::DISCOUNT),
                'difference' => $this->discountPriceDifference($total, self::DISCOUNT),
                'reason' => "The customer {$data->customer()->name()} has already spent over " . self::REVENUE_LIMIT . " € ({$data->customer()->revenue()} €)",
                'order' => $this->discountOrder($data, $affectedIds),
            ],
            'order' => $data->toArray()
        ];
    }

    /**
     * Used to calculate the new discount price
     * @param float $total
     * @param float $discount
     * @return float
     */
    private function discountPrice(float $total, float $discount): float
    {
        return ($total - floatval($this->discountPriceDifference($total, $discount)));
    }

    /**
     * The difference how much the total price will be discounted
     * @param float $total
     * @param float $discount
     * @return float
     */
    private function discountPriceDifference(float $total, float $discount): float
    {
        return $total * $discount;
    }
}