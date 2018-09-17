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

    public function match(OrderData $order): bool
    {
        if ($order->customer()->revenue() > self::REVENUE_LIMIT) {
            $this->hasMatch = true;
            $this->customer = $order->customer();
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
     * @param OrderData $order
     * @return array
     */
    private function buildDiscountAttributes(OrderData $order): array
    {
        $total = floatval($order->total());
        $affectedIds = array_pluck($order->toArray()['items'], ['product-id']);
        $discountOrder = $this->discountOrder($order, $affectedIds);

        return [
            'discount' => [
                'key' => self::KEY,
                'percent' => self::DISCOUNT * 100 . '%',
                'price' => $discountOrder['total'],
                'difference' => ($total - $discountOrder['total']),
                'reason' => "The customer {$order->customer()->name()} has already spent over " . self::REVENUE_LIMIT . " € ({$order->customer()->revenue()} €)",
                'order' => $discountOrder,
            ],
            'order' => $order->toArray()
        ];
    }
}