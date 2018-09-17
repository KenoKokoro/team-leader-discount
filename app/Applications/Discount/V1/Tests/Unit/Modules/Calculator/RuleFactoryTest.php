<?php

namespace Discount\V1\Tests\Unit\Modules\Calculator;

use Discount\V1\Modules\Calculator\Contracts\RuleFactoryInterface;
use Discount\V1\Modules\Calculator\Rules\CustomerReachedRevenue;
use Discount\V1\Modules\Calculator\Rules\SwitchesCategorySixthFree;
use Discount\V1\Modules\Calculator\Rules\ToolsCategoryTwoOrMoreProducts;
use Discount\V1\Tests\DiscountTestCase;

class RuleFactoryTest extends DiscountTestCase
{
    /**
     * @var RuleFactoryInterface
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->instance = app(RuleFactoryInterface::class);
    }

    /** @test */
    public function check_all_rule_instances_if_are_returned_properly_from_the_factory(): void
    {
        $available = [
            CustomerReachedRevenue::class,
            SwitchesCategorySixthFree::class,
            ToolsCategoryTwoOrMoreProducts::class
        ];

        foreach ($available as $className) {
            $instance = $this->instance->make($className);
            $this->assertInstanceOf($className, $instance);
        }
    }
}
