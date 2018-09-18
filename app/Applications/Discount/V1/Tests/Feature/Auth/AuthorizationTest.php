<?php


namespace Discount\V1\Tests\Feature\Auth;


use App\Http\Responses\JsonResponse;
use Discount\V1\Tests\Feature\DiscountFeatureTestCase;

class AuthorizationTest extends DiscountFeatureTestCase
{
    /** @test */
    public function it_requires_api_key_to_be_set_in_order_to_use_route_protection(): void
    {
        $old = env('API_KEY');
        putenv("API_KEY=null");

        $response = $this->jsonPost(route('v1.discounts.calculate'));
        $response->assertResponseStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        putenv("API_KEY={$old}");
    }

    /** @test */
    public function it_requires_valid_api_key_in_order_to_call_protected_route(): void
    {
        $response = $this->jsonPost(route('v1.discounts.calculate'), [], $this->bearerHeader('invalid-key'));
        $response->assertResponseStatus(JsonResponse::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function valid_api_key_should_allow_getting_actual_response_from_controller(): void
    {
        $response = $this->jsonPost(route('v1.discounts.calculate'), [], $this->bearerHeader());
        $response->assertResponseStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}