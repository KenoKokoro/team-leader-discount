<?php


namespace Discount\V1\Modules\Calculator\Data;


use Illuminate\Contracts\Support\Arrayable;

class OrderData implements Arrayable
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
        $this->original = $order;
        $this->customer = $order['customer-id'];
        $this->total = $order['total'];
        $this->items = collect($order['items']);
    }

    public function toArray()
    {
        return $this->original;
    }

    public function items(): array
    {
        return $this->items;
    }
}