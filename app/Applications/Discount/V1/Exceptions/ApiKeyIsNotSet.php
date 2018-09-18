<?php


namespace Discount\V1\Exceptions;


use App\Http\Responses\JsonResponse;
use Exception;

class ApiKeyIsNotSet extends Exception
{
    public function __construct()
    {
        parent::__construct("The API key for authorization is not set", JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}