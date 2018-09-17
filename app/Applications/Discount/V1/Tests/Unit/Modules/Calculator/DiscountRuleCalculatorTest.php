<?php

namespace Discount\V1\Tests\Unit\Modules\Calculator;

use Discount\V1\Modules\Calculator\Contracts\RuleFactoryInterface;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\DiscountRuleCalculator;
use Discount\V1\Modules\Calculator\Rules\CustomerReachedRevenue;
use Discount\V1\Modules\Calculator\Rules\SwitchesCategorySixthFree;
use Discount\V1\Modules\Calculator\Rules\ToolsCategoryTwoOrMoreProducts;
use Discount\V1\Tests\DiscountTestCase;
use Mockery\MockInterface;
use Mockery as m;

class DiscountRuleCalculatorTest extends DiscountTestCase
{
    /**
     * @var MockInterface|RuleFactoryInterface
     */
    private $ruleFactory;

    /**
     * @var DiscountRuleCalculator
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->ruleFactory = $this->mockInstance(RuleFactoryInterface::class);
        $this->instance = new DiscountRuleCalculator($this->ruleFactory);
    }

    /**
     * @expectedException \Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount
     * @test
     */
    public function it_should_throw_exception_if_there_is_no_match(): void
    {
        $order = $this->order(0);
        [$customerMock, $switchMock, $toolsMock] = $this->ruleMocks();

        $customerMock->shouldReceive('match')->with($order)->once()->andReturn(false);
        $switchMock->shouldReceive('match')->with($order)->once()->andReturn(false);
        $toolsMock->shouldReceive('match')->with($order)->once()->andReturn(false);

        $this->instance->calculate($order);
    }

    /** @test */
    public function it_should_return_discount_data_if_there_is_some_match_in_the_rules(): void
    {
        $order = $this->order(0);

        $customerMock = m::mock(CustomerReachedRevenue::class);
        $this->ruleFactory->shouldReceive('make')->with(CustomerReachedRevenue::class)->once()
                          ->andReturn($customerMock);
        $customerMock->shouldReceive('match')->with($order)->once()->andReturn(true);
        $discountData = m::mock(DiscountData::class);
        $customerMock->shouldReceive('discount')->with($order)->once()->andReturn($discountData);

        $this->instance->calculate($order);
    }

    /**
     * Return the rule mocks to be used in the tests
     * @return array
     */
    private function ruleMocks(): array
    {
        $customerMock = m::mock(CustomerReachedRevenue::class);
        $switchMock = m::mock(SwitchesCategorySixthFree::class);
        $toolsMock = m::mock(ToolsCategoryTwoOrMoreProducts::class);
        $this->ruleFactory->shouldReceive('make')->with(CustomerReachedRevenue::class)->once()
                          ->andReturn($customerMock);
        $this->ruleFactory->shouldReceive('make')->with(SwitchesCategorySixthFree::class)->once()
                          ->andReturn($switchMock);
        $this->ruleFactory->shouldReceive('make')->with(ToolsCategoryTwoOrMoreProducts::class)->once()
                          ->andReturn($toolsMock);

        return [$customerMock, $switchMock, $toolsMock];
    }
}
