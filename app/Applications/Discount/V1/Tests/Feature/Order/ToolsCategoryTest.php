<?php


namespace Discount\V1\Tests\Feature\Order;


use App\External\Client\ResponseData;
use Discount\V1\Tests\Feature\DiscountFeatureTestCase;
use Illuminate\Http\JsonResponse;

class ToolsCategoryTest extends DiscountFeatureTestCase
{
    /** @test */
    public function order_with_one_products_from_tools_category_should_not_get_discount(): void
    {
        $this->client->shouldReceive('findById')->with('customers', '3')->once()
                     ->andReturn(new ResponseData(['result' => $this->customerResponse('3', '0')]));
        $this->client->shouldReceive('getByIds')->with('products', ['A101'])->once()
                     ->andReturn(new ResponseData(['result' => [$this->productsResponse()[0]]]));

        $response = $this->jsonPost(route('v1.discounts.calculate'), $this->notEligibleInput(), $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_OK);
        $response = $this->jsonData($response->response->getContent());

        $this->assertEquals('The order did not match any rule for discount', $response['message']);
    }

    /** @test */
    public function order_with_two_or_more_products_of_tools_category_should_get_discount(): void
    {
        $this->client->shouldReceive('findById')->with('customers', '3')->once()
                     ->andReturn(new ResponseData(['result' => $this->customerResponse('3', '0')]));
        $this->client->shouldReceive('getByIds')->with('products', ['A101', 'A102'])->once()
                     ->andReturn(new ResponseData(['result' => $this->productsResponse()]));

        $response = $this->jsonPost(route('v1.discounts.calculate'), $this->input(), $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_OK);
        $response->seeJsonStructure(['message', 'result' => ['discount' => ['order'], 'order']]);
        $response = $this->jsonData($response->response->getContent());

        $this->assertEquals('67.05', $response['result']['discount']['order']['total']);
    }

    /**
     * Order input stub
     * @return array
     */
    private function input(): array
    {
        return [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '2',
                    'unit-price' => '9.75',
                    'total' => '19.50'
                ],
                [
                    'product-id' => 'A102',
                    'quantity' => '1',
                    'unit-price' => '49.50',
                    'total' => '49.50'
                ]
            ],
            'total' => '69.00'
        ];
    }

    /**
     * Not eligible order input stub
     * @return array
     */
    private function notEligibleInput(): array
    {
        return [
            'id' => '3',
            'customer-id' => '3',
            'items' => [
                [
                    'product-id' => 'A101',
                    'quantity' => '1',
                    'unit-price' => '9.75',
                    'total' => '19.50'
                ],
            ],
            'total' => '69.00'
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
                'id' => 'A101',
                'description' => 'Screwdriver',
                'category' => '1',
                'price' => '9.75'
            ],
            [
                'id' => 'A102',
                'description' => 'Electric screwdriver',
                'category' => '1',
                'price' => '49.50'
            ],
        ];
    }
}