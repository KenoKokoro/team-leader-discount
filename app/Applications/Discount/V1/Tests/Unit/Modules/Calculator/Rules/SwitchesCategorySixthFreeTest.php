<?php

namespace Discount\V1\Tests\Unit\Modules\Calculator\Rules;

use Discount\V1\Modules\Calculator\Rules\SwitchesCategorySixthFree;
use Discount\V1\Tests\DiscountTestCase;

class SwitchesCategorySixthFreeTest extends DiscountTestCase
{
    /**
     * @var SwitchesCategorySixthFree
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->instance = new SwitchesCategorySixthFree($this->generic);
    }

    /** @test */
    public function it_should_contain_products_with_category_id_2_in_order_to_be_eligible_for_discount(): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'A', 'quantity' => '10', 'unit-price' => '9.75', 'total' => '97.50'],
                ['product-id' => 'C', 'quantity' => '10', 'unit-price' => '9.75', 'total' => '97.50']
            ],
            [
                ['id' => 'A', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '9.75'],
                ['id' => 'C', 'description' => 'Lorem ipsum', 'category' => '3', 'price' => '9.75']
            ]
        );

        $this->assertFalse($this->instance->match($order));
    }

    /** @test */
    public function it_should_contain_more_than_5_products_from_category_id_2_in_order_to_be_eligible_for_discount(
    ): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'B1', 'quantity' => '4', 'unit-price' => '9.75', 'total' => '97.50'],
                ['product-id' => 'B2', 'quantity' => '3', 'unit-price' => '9.75', 'total' => '97.50']
            ],
            [
                ['id' => 'B1', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75'],
                ['id' => 'B2', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75']
            ]
        );

        $this->assertFalse($this->instance->match($order));
    }

    /**
     * @expectedException \Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount
     * @test
     */
    public function it_requires_products_to_match_in_order_to_be_able_to_calculate_discount(): void
    {
        $order = $this->order(0);

        $this->instance->discount($order);
    }

    /** @test */
    public function it_should_return_discount_data_if_there_is_products_match_for_category_with_id_2(): void
    {
        $order = $this->order(0,
            [
                ['product-id' => 'B1', 'quantity' => '7', 'unit-price' => '9.75', 'total' => '68.25'],
                ['product-id' => 'B2', 'quantity' => '6', 'unit-price' => '8.55', 'total' => '51.30'],
                ['product-id' => 'B3', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                ['product-id' => 'A', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
            ],
            [
                ['id' => 'B1', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75'],
                ['id' => 'B2', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '8.55'],
                ['id' => 'B3', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '7.15'],
                ['id' => 'A', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '5.55']
            ],
            203.65
        );

        $this->assertTrue($this->instance->match($order));

        $data = $this->instance->discount($order);
        $this->assertEquals([
            'discount' => [
                'key' => 'QUANTITY_REACHED_FOR_SWITCHES_CATEGORY',
                'percent' => '9%',
                'price' => '185.35',
                'difference' => '18.30',
                'reason' => 'When you buy five products from Switches category, you get a sixth for free.',
                'order' => [
                    'id' => '1',
                    'items' => [
                        ['product-id' => 'B1', 'quantity' => '7', 'unit-price' => '9.75', 'total' => '58.50'],
                        ['product-id' => 'B2', 'quantity' => '6', 'unit-price' => '8.55', 'total' => '42.75'],
                        ['product-id' => 'B3', 'quantity' => '4', 'unit-price' => '7.15', 'total' => '28.60'],
                        ['product-id' => 'A', 'quantity' => '10', 'unit-price' => '5.55', 'total' => '55.50']
                    ],
                    'total' => '185.35',
                    'customer-id' => '1',
                ]
            ],
            'order' => $order->toArray()
        ], $data->toArray());
    }
}
