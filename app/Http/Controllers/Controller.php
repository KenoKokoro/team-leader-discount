<?php

namespace App\Http\Controllers;

use App\Http\Responses\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function json(): JsonResponse
    {
        return app(JsonResponse::class);
    }
}
