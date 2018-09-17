<?php


namespace Discount\V1\Tests;


use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Tests\TestCase;

abstract class DiscountTestCase extends TestCase
{
    /**
     * @var GenericFactoryInterface
     */
    protected $generic;

    public function setUp()
    {
        parent::setUp();
        $this->generic = app(GenericFactoryInterface::class);
    }

    /**
     * Generate order stub for testing
     * @param int   $revenue
     * @param array $items
     * @param array $products
     * @param float $total
     * @return OrderData
     */
    protected function order(int $revenue, array $items = [], array $products = [], float $total = 0.00): OrderData
    {
        /** @var OrderData $instance */
        $instance = $this->generic->make(OrderData::class, [
            'customer' => ['revenue' => $revenue, 'name' => 'John Doe'],
            'customer-id' => '1',
            'items' => $items,
            'products' => $products,
            'total' => $total,
            'id' => '1'
        ]);

        return $instance;
    }
}