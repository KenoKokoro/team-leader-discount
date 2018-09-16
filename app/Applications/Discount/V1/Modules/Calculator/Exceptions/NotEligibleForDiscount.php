<?php


namespace Discount\V1\Modules\Calculator\Exceptions;


use App\Http\Responses\JsonResponse;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Exception;

class NotEligibleForDiscount extends Exception
{
    /**
     * @var OrderData
     */
    private $order;

    public function __construct(OrderData $order)
    {
        $this->order = $order;
        parent::__construct("The order did not match any rule for discount", JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getOrder(): array
    {
        return $this->order->toArray();
    }
}