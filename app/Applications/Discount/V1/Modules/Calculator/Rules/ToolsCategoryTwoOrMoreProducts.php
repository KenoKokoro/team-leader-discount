<?php


namespace Discount\V1\Modules\Calculator\Rules;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;
use Illuminate\Support\Collection;

class ToolsCategoryTwoOrMoreProducts extends BaseRule
{
    const CATEGORY_ID = 1;
    const PRODUCT_LIMIT = 1;
    const DISCOUNT = 0.2;
    const KEY = 'QUANTITY_REACHED_FOR_TOOLS_CATEGORY';

    /**
     * The cheapest product id
     * @var string
     */
    private $productId;

    public function match(OrderData $order): bool
    {
        $product = $this->findMatchingProduct($order->items()->get(self::CATEGORY_ID), self::PRODUCT_LIMIT);
        if ( ! is_null($product)) {
            $this->productId = $product['product-id'];
            $this->hasMatch = true;
        }

        return $this->hasMatch;
    }

    public function discount(OrderData $order): DiscountData
    {
        if (empty($this->productId)) {
            throw new NotEligibleForDiscount($order);
        }

        return $this->data($this->buildDiscountAttributes($order, $this->productId));
    }

    protected function discountedUnitPrice(array $item): float
    {
        $price = floatval($item['unit-price']);

        if ($item['quantity'] === '1') {
            return ($price - ($price * self::DISCOUNT));
        }

        return $price;
    }

    protected function discountedItemTotal(array $item): float
    {
        $price = floatval($item['unit-price']);

        return floatval($item['total']) - ($price * self::DISCOUNT);
    }

    /**
     * Find the cheapest product if there are more products than the limit
     * @param Collection|null $items
     * @param int             $limit
     * @return array|null
     */
    private function findMatchingProduct(?Collection $items, int $limit): ?array
    {
        if (is_null($items)) {
            return null;
        }

        $quantity = $items->sum('quantity');

        if ($quantity <= $limit) {
            return null;
        }

        return $this->findTheItemWithCheapestUnitPrice($items);
    }

    /**
     * Sort the items by unit price ascending and take the first item, since it will be the cheapest
     * @param Collection $items
     * @return array
     */
    private function findTheItemWithCheapestUnitPrice(Collection $items): array
    {
        return $items->sortBy('unit-price')->first();
    }

    /**
     * Generate the discount attributes and prepare the attributes for response
     * @param OrderData $order
     * @param string    $cheapestId
     * @return array
     */
    private function buildDiscountAttributes(OrderData $order, string $cheapestId): array
    {
        $total = floatval($order->total());
        $affectedIds = [$cheapestId];
        $discountOrder = $this->discountOrder($order, $affectedIds);
        $percent = number_format((($total - $discountOrder['total']) / $total) * 100, 0);

        return [
            'discount' => [
                'key' => self::KEY,
                'percent' => "{$percent}%",
                'price' => $discountOrder['total'],
                'difference' => ($total - $discountOrder['total']),
                'reason' => 'When you buy two or more products from Tools category, you get a 20% discount on the cheapest product.',
                'order' => $discountOrder,
            ],
            'order' => $order->toArray()
        ];
    }
}