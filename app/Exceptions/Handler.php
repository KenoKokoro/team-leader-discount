<?php

namespace App\Exceptions;

use App\Http\Responses\JsonResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse as LumenJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return LumenJsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            return $this->jsonResponse($exception);
        }

        return parent::render($request, $exception);
    }

    private function jsonResponse(Exception $exception): LumenJsonResponse
    {
        $response = new JsonResponse();

        if ($exception instanceof ValidationException) {
            return $response->unprocessableEntity($exception->getMessage(), [
                'validator' => $exception->validator->getMessageBag()
            ]);
        } elseif ($exception instanceof AuthenticationException) {
            return $response->unauthorized($exception->getMessage());
        } elseif ($exception instanceof NotFoundHttpException) {
            return $response->notFound('Route does not exist.');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $response->methodNotAllowed('Method not allowed on this route.');
        }

        return $response->internalError($exception->getMessage());
    }
}
