<?php


namespace Discount\V1\Tests\Feature\Order;


use App\External\Client\ResponseData;
use App\Http\Responses\JsonResponse;
use Discount\V1\Tests\Feature\DiscountFeatureTestCase;

class CustomerRevenueTest extends DiscountFeatureTestCase
{
    /** @test */
    public function customer_without_revenue_above_1000_should_not_get_discount(): void
    {
        $this->client->shouldReceive('findById')->with('customers', '2')->once()
                     ->andReturn(new ResponseData(['result' => $this->customerResponse('2', '999.99')]));
        $this->client->shouldReceive('getByIds')->with('products', ['B102'])->once()
                     ->andReturn(new ResponseData(['result' => $this->productsResponse()]));

        $response = $this->jsonPost(route('v1.discounts.calculate'), $this->input(), $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_OK);
        $response = $this->jsonData($response->response->getContent());

        $this->assertEquals('The order did not match any rule for discount', $response['message']);
    }

    /** @test */
    public function customer_with_revenue_above_1000_should_get_discount(): void
    {
        $this->client->shouldReceive('findById')->with('customers', '2')->once()
                     ->andReturn(new ResponseData(['result' => $this->customerResponse('2', '1001.00')]));
        $this->client->shouldReceive('getByIds')->with('products', ['B102'])->once()
                     ->andReturn(new ResponseData(['result' => $this->productsResponse()]));

        $response = $this->jsonPost(route('v1.discounts.calculate'), $this->input(), $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_OK);
        $response->seeJsonStructure(['message', 'result' => ['discount' => ['order'], 'order']]);
        $response = $this->jsonData($response->response->getContent());

        $this->assertEquals('22.45', $response['result']['discount']['order']['total']);
    }

    private function input(): array
    {
        return [
            'id' => '2',
            'customer-id' => '2',
            'items' => [
                [
                    'product-id' => 'B102',
                    'quantity' => '5',
                    'unit-price' => '4.99',
                    'total' => '24.95'
                ]
            ],
            'total' => '24.95'
        ];
    }

    /**
     * The stub that should be returned from the external API
     * @return array
     */
    private function productsResponse(): array
    {
        return [
            [
                'id' => 'B102',
                'description' => 'Press button',
                'category' => '2',
                'price' => '4.99'
            ]
        ];
    }
}