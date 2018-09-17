<?php

namespace Discount\V1\Tests\Unit\Modules\Calculator\Rules;

use Discount\V1\Modules\Calculator\Rules\ToolsCategoryTwoOrMoreProducts;
use Discount\V1\Tests\DiscountTestCase;

class ToolsCategoryTwoOrMoreProductsTest extends DiscountTestCase
{
    /**
     * @var ToolsCategoryTwoOrMoreProducts
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->instance = new ToolsCategoryTwoOrMoreProducts($this->generic);
    }

    /** @test */
    public function it_should_contain_products_with_category_id_1_in_order_to_be_eligible_for_discount(): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'B', 'quantity' => '10', 'unit-price' => '9.75', 'total' => '97.50'],
                ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '9.75', 'total' => '97.50']
            ],
            [
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75'],
                ['id' => 'C', 'description' => 'Lorem ipsum', 'category' => '3', 'price' => '9.75']
            ]
        );

        $this->assertFalse($this->instance->match($order));
    }

    /** @test */
    public function it_should_contain_two_or_more_products_from_category_id_1_in_order_to_be_eligible_for_discount(
    ): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'A', 'quantity' => '1', 'unit-price' => '9.75', 'total' => '97.50'],
                ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '9.75', 'total' => '97.50']
            ],
            [
                ['id' => 'A', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '9.75'],
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75']
            ]
        );

        $this->assertFalse($this->instance->match($order));
    }

    /**
     * @expectedException \Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount
     * @test
     */
    public function it_requires_the_cheapest_product_to_be_set_in_order_to_calculate_the_discount(): void
    {
        $order = $this->order(0);

        $this->instance->discount($order);
    }

    /** @test */
    public function it_should_return_discount_data_if_there_are_two_or_more_products_for_category_id_1(): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'A1', 'quantity' => '1', 'unit-price' => '7.75', 'total' => '7.75'],
                ['product-id' => 'A2', 'quantity' => '1', 'unit-price' => '8.55', 'total' => '8.55'],
                ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
            ],
            [
                ['id' => 'A1', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '7.75'],
                ['id' => 'A2', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '8.55'],
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '7.15'],
                ['id' => 'C', 'description' => 'Lorem ipsum', 'category' => '3', 'price' => '5.55']
            ],
            100.40
        );

        $this->assertTrue($this->instance->match($order));
        $data = $this->instance->discount($order);
        $this->assertEquals([
            'discount' => [
                'key' => 'QUANTITY_REACHED_FOR_TOOLS_CATEGORY',
                'percent' => '2%',
                'price' => '98.85',
                'difference' => '1.55',
                'reason' => 'When you buy two or more products from Tools category, you get a 20% discount on the cheapest product.',
                'order' => [
                    'id' => '1',
                    'items' => [
                        ['product-id' => 'A1', 'quantity' => '1', 'unit-price' => '6.20', 'total' => '6.20'],
                        ['product-id' => 'A2', 'quantity' => '1', 'unit-price' => '8.55', 'total' => '8.55'],
                        ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                        ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
                    ],
                    'total' => '98.85'
                ]
            ],
            'order' => $order->toArray()
        ], $data->toArray());
    }

    /** @test */
    public function it_should_return_discount_data_if_there_are_two_or_more_products_for_category_id_1_when_cheapset_product_has_quantity_more_than_one(
    ): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'A1', 'quantity' => '3', 'unit-price' => '7.75', 'total' => '23.25'],
                ['product-id' => 'A2', 'quantity' => '1', 'unit-price' => '8.55', 'total' => '8.55'],
                ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
            ],
            [
                ['id' => 'A1', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '7.75'],
                ['id' => 'A2', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '8.55'],
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '7.15'],
                ['id' => 'C', 'description' => 'Lorem ipsum', 'category' => '3', 'price' => '5.55']
            ],
            115.90
        );

        $this->assertTrue($this->instance->match($order));
        $data = $this->instance->discount($order);
        $this->assertEquals([
            'discount' => [
                'key' => 'QUANTITY_REACHED_FOR_TOOLS_CATEGORY',
                'percent' => '1%',
                'price' => '114.35',
                'difference' => '1.55',
                'reason' => 'When you buy two or more products from Tools category, you get a 20% discount on the cheapest product.',
                'order' => [
                    'id' => '1',
                    'items' => [
                        ['product-id' => 'A1', 'quantity' => '3', 'unit-price' => '7.75', 'total' => '21.70'],
                        ['product-id' => 'A2', 'quantity' => '1', 'unit-price' => '8.55', 'total' => '8.55'],
                        ['product-id' => 'B', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                        ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
                    ],
                    'total' => '114.35'
                ]
            ],
            'order' => $order->toArray()
        ], $data->toArray());
    }
}
