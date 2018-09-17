<?php

namespace Discount\V1\Tests\Unit\Modules\Calculator\Rules;

use Discount\V1\Modules\Calculator\Rules\CustomerReachedRevenue;
use Discount\V1\Tests\DiscountTestCase;

class CustomerReachedRevenueTest extends DiscountTestCase
{
    /**
     * @var CustomerReachedRevenue
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->instance = new CustomerReachedRevenue($this->generic);
    }

    /** @test */
    public function a_customer_without_revenue_above_1000_is_not_eligible_for_discount(): void
    {
        $order = $this->order(999);

        $this->assertFalse($this->instance->match($order));
    }

    /**
     * @expectedException \Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount
     * @test
     */
    public function it_requires_customer_in_order_to_be_able_to_calculate_discount(): void
    {
        $order = $this->order(1001);

        $this->instance->discount($order);
    }

    /** @test */
    public function it_should_return_discount_data_if_there_is_a_match_and_customer_set(): void
    {
        $order = $this->order(1001,
            [
                ['product-id' => 'A', 'quantity' => '2', 'unit-price' => '9.75', 'total' => '19.50'],
                ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '7.55', 'total' => '30.20'],
            ],
            [
                ['id' => 'A', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '9.75'],
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '7.55'],
            ],
            49.7
        );

        $this->assertTrue($this->instance->match($order));
        $data = $this->instance->discount($order);
        $this->assertEquals([
            'discount' => [
                'key' => 'CUSTOMER_PASSED_REVENUE_LIMIT',
                'percent' => '10%',
                'price' => '44.73',
                'difference' => '4.97',
                'reason' => 'The customer John Doe has already spent over 1000 € (1001 €)',
                'order' => [
                    'id' => '1',
                    'items' => [
                        ['product-id' => 'A', 'quantity' => '2', 'unit-price' => '8.77', 'total' => '17.55'],
                        ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '6.79', 'total' => '27.18'],
                    ],
                    'total' => '44.73'
                ]
            ],
            'order' => $order->toArray()
        ], $data->toArray());
    }
}
