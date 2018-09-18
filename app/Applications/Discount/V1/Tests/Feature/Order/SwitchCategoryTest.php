<?php


namespace Discount\V1\Tests\Feature\Order;


use App\External\Client\ResponseData;
use App\Http\Responses\JsonResponse;
use Discount\V1\Tests\Feature\DiscountFeatureTestCase;

class SwitchCategoryTest extends DiscountFeatureTestCase
{
    /** @test */
    public function order_with_5_products_from_switches_category_you_get_sixth_for_free(): void
    {
        $this->client->shouldReceive('findById')->with('customers', '1')->once()
                     ->andReturn(new ResponseData(['result' => $this->customerResponse('1', '0')]));
        $this->client->shouldReceive('getByIds')->with('products', ['B102'])->once()
                     ->andReturn(new ResponseData(['result' => $this->productsResponse()]));

        $response = $this->jsonPost(route('v1.discounts.calculate'), $this->input(), $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_OK);
        $response->seeJsonStructure(['message', 'result' => ['discount' => ['order'], 'order']]);
        $response = $this->jsonData($response->response->getContent());

        $this->assertEquals('44.91', $response['result']['discount']['order']['total']);
    }

    /**
     * Order input stub
     * @return array
     */
    public function input(): array
    {
        return [
            "id" => "1",
            "customer-id" => "1",
            "items" => [
                [
                    "product-id" => "B102",
                    "quantity" => "10",
                    "unit-price" => "4.99",
                    "total" => "49.90"
                ]
            ],
            "total" => "49.90"
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
            ],
        ];
    }
}