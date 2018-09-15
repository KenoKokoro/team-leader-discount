<?php


namespace Discount\V1\Modules\Calculator\Exceptions;


use App\Http\Responses\JsonResponse;
use Exception;

class NotEligibleForDiscount extends Exception
{
    public function __construct()
    {
        parent::__construct("The order did not match any rule for discount", JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}