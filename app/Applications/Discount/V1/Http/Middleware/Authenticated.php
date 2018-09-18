<?php


namespace Discount\V1\Http\Middleware;


use Closure;
use Discount\V1\Exceptions\ApiKeyIsNotSet;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Authenticated
{
    /**
     * @param Closure $next
     * @param Request $request
     * @return JsonResponse
     * @throws ApiKeyIsNotSet
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $this->validate($apiKey = env('API_KEY'));
        $value = $request->headers->get('authorization');

        if ($value !== "Bearer {$apiKey}") {
            throw new AuthenticationException();
        }

        return $next($request);
    }

    /**
     * @param null|string $apiKey
     * @throws ApiKeyIsNotSet
     */
    private function validate(?string $apiKey): void
    {
        if (empty($apiKey)) {
            throw new ApiKeyIsNotSet();
        }
    }
}