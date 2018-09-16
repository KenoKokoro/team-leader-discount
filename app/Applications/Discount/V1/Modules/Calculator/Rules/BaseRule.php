<?php


namespace Discount\V1\Modules\Calculator\Rules;


use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Contracts\DiscountRule;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

abstract class BaseRule implements DiscountRule
{
    /**
     * @var GenericFactoryInterface
     */
    private $generic;

    /**
     * Determines if there is a match in the rule or not
     * @var bool
     */
    protected $hasMatch = false;

    public function __construct(GenericFactoryInterface $generic)
    {
        $this->generic = $generic;
    }

    /**
     * Make instance of the discount data and return it
     * @param array $attributes
     * @return DiscountData
     */
    protected function data(array $attributes): DiscountData
    {
        /** @var DiscountData $data */
        $data = $this->generic->make(DiscountData::class, $attributes);

        return $data;
    }

    /**
     * Return the new discount order
     * @param OrderData $order
     * @param array     $affectedIds
     * @return array
     */
    protected function discountOrder(OrderData $order, array $affectedIds): array
    {
        $items = $this->mapNewDiscountPrices($order->toArray()['items'], $affectedIds);
        $newTotal = array_sum(array_pluck($items, 'total'));

        return array_merge($order->toArray(), ['items' => $items, 'total' => $newTotal]);
    }

    /**
     * Map the new discount prices for each product if there is discount on it
     * @param array $items
     * @param array $affectedIds
     * @return array
     */
    private function mapNewDiscountPrices(array $items, array $affectedIds): array
    {
        return collect($items)->map(function($item) use ($affectedIds) {
            if ( ! in_array($item['product-id'], $affectedIds)) {
                return $item;
            }

            $item['unit-price'] = $this->discountedUnitPrice($item);
            $item['total'] = $this->discountedItemTotal($item);

            return $item;
        })->toArray();
    }

    /**
     * Single unit price discount calculations
     * @param array $item
     * @return float
     */
    abstract protected function discountedUnitPrice(array $item): float;

    /**
     * Total price per item order
     * @param array $item
     * @return float
     */
    abstract protected function discountedItemTotal(array $item): float;
}