<?php


namespace Discount\V1\Tests\Feature\Exceptions;


use App\Http\Responses\JsonResponse;
use Discount\V1\Tests\Feature\DiscountFeatureTestCase;

class ExceptionResponsesTest extends DiscountFeatureTestCase
{
    /** @test */
    public function it_should_return_404_response_on_missing_route(): void
    {
        $missingUrl = 'v1/api/wrong/url';
        $this->jsonGet($missingUrl)->assertResponseStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_should_return_405_when_method_is_not_allowed_on_route(): void
    {
        $route = route('v1.discounts.calculate');
        $this->jsonGet($route)->assertResponseStatus(JsonResponse::HTTP_METHOD_NOT_ALLOWED);
    }
}