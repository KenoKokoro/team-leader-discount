<?php


namespace Discount\V1\Modules\Calculator\Rules;


use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;
use Illuminate\Support\Collection;

class SwitchesCategorySixthFree extends BaseRule
{
    const CATEGORY_ID = 2;
    const PRODUCT_LIMIT = 5;
    const KEY = 'QUANTITY_REACHED_FOR_SWITCHES_CATEGORY';

    /**
     * The matched products for discount
     * @var array
     */
    private $products = [];

    public function match(OrderData $data): bool
    {
        $products = $this->findMatchingProducts($data->items()->get(self::CATEGORY_ID), self::PRODUCT_LIMIT);

        if ( ! is_null($products)) {
            $this->hasMatch = true;
            $this->products = $products;
        }

        return $this->hasMatch;
    }

    public function discount(OrderData $order): DiscountData
    {
        if (empty($this->products)) {
            throw new NotEligibleForDiscount($order);
        }

        $attributes = $this->buildDiscountAttributes($order, $this->products);

        return $this->data($attributes);
    }

    protected function discountedUnitPrice(array $item): float
    {
        return floatval($item['unit-price']);
    }

    protected function discountedItemTotal(array $item): float
    {
        return floatval($item['total']) - floatval($item['unit-price']);
    }

    /**
     * Find all items that have quantity bigger than the given limit (The items that will basically be discounted)
     * @param Collection|null $items
     * @param int             $limit
     * @return array|null
     */
    private function findMatchingProducts(?Collection $items, int $limit): ?array
    {
        if (is_null($items)) {
            return null;
        }

        foreach ($items as $item) {
            if ($item['quantity'] > $limit) {
                $products[] = $item;
            }
        }

        return $products ?? null;
    }

    /**
     * Generate the discount attributes and prepare the attributes for response
     * @param OrderData $data
     * @param array     $products The products for discount
     * @return array
     */
    private function buildDiscountAttributes(OrderData $data, array $products): array
    {
        $total = floatval($data->total());
        $discountPrice = $this->discountPrice($total, $products);
        $affectedIds = array_pluck($products, ['product-id']);
        $percent = number_format((($total - $discountPrice) / $total) * 100, 0);

        return [
            'discount' => [
                'key' => self::KEY,
                'percent' => "{$percent}%",
                'price' => $discountPrice,
                'difference' => ($total - $discountPrice),
                'reason' => "When you buy five products from Switches category, you get a sixth for free.",
                'order' => $this->discountOrder($data, $affectedIds),
            ],
            'order' => $data->toArray()
        ];
    }

    /**
     * Used to calculate the new discount price
     * @param float $total
     * @param array $products
     * @return float
     */
    private function discountPrice(float $total, array $products): float
    {
        $productPrices = array_pluck($products, 'unit-price');

        return $total - array_sum($productPrices);
    }
}