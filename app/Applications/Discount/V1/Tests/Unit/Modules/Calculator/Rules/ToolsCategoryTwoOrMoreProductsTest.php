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
                ['id' => 'A1', 'description' => 'Lorem ipsum', 'category' => '1', 'price' => '9.75'],
                ['id' => 'B', 'description' => 'Lorem ipsum', 'category' => '2', 'price' => '9.75']
            ]
        );

        $this->assertFalse($this->instance->match($order));
    }
}
