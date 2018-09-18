<?php

namespace Discount\V1\Tests\Unit\Http\Controllers;

use App\Http\Responses\JsonResponse;
use Discount\V1\BLL\Discount\DiscountInterface;
use Discount\V1\Http\Controllers\DiscountsController;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;
use Discount\V1\Tests\DiscountTestCase;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Mockery as m;

class DiscountsControllerTest extends DiscountTestCase
{
    /**
     * @var DiscountInterface|MockInterface
     */
    private $discount;

    /**
     * @var JsonResponse|MockInterface
     */
    private $json;

    /**
     * @var DiscountsController
     */
    private $instance;

    public function setUp()
    {
        parent::setUp();
        $this->discount = $this->mockInstance(DiscountInterface::class);
        $this->json = $this->mockInstance(JsonResponse::class);
        $this->instance = new DiscountsController($this->discount);
    }

    /**
     * @expectedException \Illuminate\Validation\ValidationException
     * @test
     */
    public function it_requires_data_in_order_to_proceed_with_discount_calculation(): void
    {
        $request = m::mock(Request::class);

        $request->shouldReceive('all')->once()->andReturn([]);
        $this->discount->shouldReceive('calculationRules')->once()->andReturn($this->calculationRulesStub());

        $this->instance->calculate($request);
    }

    /** @test */
    public function it_should_raise_an_exception_if_order_is_not_eligible_for_discount(): void
    {
        $orderStub = $this->order(0, $this->itemsStub())->toArray();
        $orderArgs = array_only($orderStub, ['customer-id', 'items', 'total', 'id']);
        $orderDataStub = m::mock(OrderData::class);
        $request = m::mock(Request::class);

        $request->shouldReceive('all')->andReturn($orderStub);
        $this->discount->shouldReceive('calculationRules')->once()->andReturn($this->calculationRulesStub());
        $request->shouldReceive('only')->twice()->andReturn($orderArgs);
        $this->discount->shouldReceive('calculateFor')->with($orderArgs)->once()
                       ->andThrow(new NotEligibleForDiscount($orderDataStub));
        $orderDataStub->shouldReceive('toArray')->andReturn($orderArgs);
        $this->json->shouldReceive('ok')->with('The order did not match any rule for discount',
            ['result' => $orderArgs])->once(); # We do not care about the response now

        $this->instance->calculate($request);
    }

    /** @test */
    public function it_should_return_order_data_if_there_is_discount_found_for_the_order(): void
    {
        $orderStub = $this->order(0, $this->itemsStub())->toArray();
        $request = m::mock(Request::class);
        $orderArgs = array_only($orderStub, ['customer-id', 'items', 'total', 'id']);
        $discountDataStub = m::mock(DiscountData::class);

        $request->shouldReceive('all')->andReturn($orderStub);
        $this->discount->shouldReceive('calculationRules')->once()->andReturn($this->calculationRulesStub());
        $request->shouldReceive('only')->twice()->andReturn($orderArgs);
        $this->discount->shouldReceive('calculateFor')->with($orderArgs)->once()
                       ->andReturn($discountDataStub);
        $discountDataStub->shouldReceive('toArray')->andReturn([]);
        $this->json->shouldReceive('ok')->with('', ['result' => []])->once(); # We do not care about the response now

        $this->instance->calculate($request);
    }

    /**
     * Return the calculation rules
     * @return array
     */
    private function calculationRulesStub(): array
    {
        return [
            'id' => 'required',
            'customer-id' => 'required',
            'total' => 'required',
            'items' => 'required|array',
            'items.*.product-id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.unit-price' => 'required',
            'items.*.total' => 'required',
        ];
    }

    /**
     * Return some items stub
     * @return array
     */
    private function itemsStub(): array
    {
        return [
            ['product-id' => 'A', 'quantity' => '1', 'unit-price' => '5.23', 'total' => '5.23'],
            ['product-id' => 'B', 'quantity' => '1', 'unit-price' => '8.55', 'total' => '8.55'],
        ];
    }
}
