<?php

namespace Discount\V1\Tests\Unit\BLL\Discount;

use App\External\Client\ClientInterface;
use App\External\Client\ResponseData;
use App\Generics\GenericFactoryInterface;
use Discount\V1\BLL\Discount\DiscountInterface;
use Discount\V1\BLL\Discount\DiscountLayer;
use Discount\V1\Modules\Calculator\Contracts\DiscountCalculation;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Tests\DiscountTestCase;
use Mockery as m;
use Mockery\MockInterface;

class DiscountLayerTest extends DiscountTestCase
{
    /**
     * @var DiscountInterface
     */
    private $instance;

    /**
     * @var ClientInterface|MockInterface
     */
    private $client;

    /**
     * @var DiscountCalculation|MockInterface
     */
    private $calculator;

    /**
     * @var GenericFactoryInterface|MockInterface
     */
    private $genericMock;

    public function setUp()
    {
        parent::setUp();
        $this->client = $this->mockInstance(ClientInterface::class);
        $this->calculator = $this->mockInstance(DiscountCalculation::class);
        $this->genericMock = $this->mockInstance(GenericFactoryInterface::class);
        $this->instance = new DiscountLayer($this->calculator, $this->client, $this->genericMock);
    }

    /** @test */
    public function it_should_return_all_discount_layer_calculation_rules(): void
    {
        $this->assertEquals([
            'id' => 'required',
            'customer-id' => 'required',
            'total' => 'required',
            'items' => 'required|array',
            'items.*.product-id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.unit-price' => 'required',
            'items.*.total' => 'required',
        ], $this->instance->calculationRules());
    }

    /**
     * We do not care about the data here, just mock everything and be sure to cover with the integration tests
     * @test
     */
    public function it_should_return_arrayable_instance_on_discount_layer_calculation(): void
    {
        $customerResponse = m::mock(ResponseData::class);
        $productResponse = m::mock(ResponseData::class);
        $orderMock = m::mock(OrderData::class);
        $discountMock = m::mock(DiscountData::class);

        $this->client->shouldReceive('findById')->with('customers', '2')->once()->andReturn($customerResponse);
        $customerResponse->shouldReceive('result')->once()->andReturn([]);
        $this->client->shouldReceive('getByIds')->with('products', ['B1', 'B2'])->once()->andReturn($productResponse);
        $productResponse->shouldReceive('result')->once()->andReturn([]);
        $this->genericMock->shouldReceive('make')->with(OrderData::class,
            [
                'customer-id' => '2',
                'items' => [['product-id' => 'B1'], ['product-id' => 'B2']],
                'customer' => [],
                'products' => []
            ])->once()->andReturn($orderMock);
        $this->calculator->shouldReceive('calculate')->with($orderMock)->once()->andReturn($discountMock);

        $this->instance->calculateFor([
            'customer-id' => '2',
            'items' => [['product-id' => 'B1'], ['product-id' => 'B2']]
        ]);
    }
}
