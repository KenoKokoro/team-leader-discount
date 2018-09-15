<?php


namespace Discount\V1\Modules\Calculator\Data;


use App\Generics\SingleDataObject;
use Illuminate\Support\Collection;

class OrderData extends SingleDataObject
{
    /**
     * @var string
     */
    private $customer;

    /**
     * @var string
     */
    private $total;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $items;

    /**
     * @var array
     */
    private $original;

    public function __construct(array $order)
    {
        $this->original = array_except($order, ['products', 'customer']);
        $this->customer = new CustomerData($order['customer']);
        $this->total = $order['total'];
        $this->items = $this->mapCategoryAsValueOnItems(collect($order['items']), $order['products']);
        parent::__construct($this->items->toArray());
    }

    /**
     * Return the original data that was send in the request
     * @return array
     */
    public function toArray(): array
    {
        return $this->original;
    }

    /**
     * All generated items
     * @return Collection
     */
    public function items(): Collection
    {
        return $this->items;
    }

    /**
     * The customer data that was fetch from the external API
     * @return CustomerData
     */
    public function customer(): CustomerData
    {
        return $this->customer;
    }

    /**
     * The total price of the order
     * @return string
     */
    public function total(): string
    {
        return $this->total;
    }

    /**
     * Since some of our rules require category check, we map the category value into each item and then group all items by category
     * @param Collection $items
     * @param array      $products
     * @return Collection
     */
    private function mapCategoryAsValueOnItems(Collection $items, array $products): Collection
    {
        $items = $items->map(function(array $item) use ($products) {
            $item['category'] = array_first($products, function(array $product) use ($item) {
                return $product['id'] === $item['product-id'];
            })['category'];

            return $item;
        })->groupBy('category');

        return $items;
    }
}