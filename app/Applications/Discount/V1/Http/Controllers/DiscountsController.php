<?php


namespace Discount\V1\Http\Controllers;


use App\Http\Controllers\Controller;
use Discount\V1\BLL\Discount\DiscountInterface;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    /**
     * @var DiscountInterface
     */
    private $discount;

    public function __construct(DiscountInterface $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \App\External\Client\Exceptions\ServiceNotFound
     * @throws \Illuminate\Validation\ValidationException
     */
    public function calculate(Request $request): JsonResponse
    {
        $this->validate($request, $this->discount->calculationRules());
        try {
            $data = $this->discount->calculateFor($request->only(['customer-id', 'items', 'total']));

            return $this->json()->ok('', ['result' => $data->toArray()]);
        } catch (NotEligibleForDiscount $exception) {
            return $this->json()->ok($exception->getMessage());
        }
    }
}