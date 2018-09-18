<?php


namespace App\Http\Responses;


use Illuminate\Http\JsonResponse as LumenJsonResponse;

class JsonResponse extends LumenJsonResponse
{
    public function ok(string $message = '', array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Successfully executed.';
        }
        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_OK);
    }

    public function created(string $message = '', array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Successfully created.';
        }
        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_CREATED);
    }

    public function forbidden(string $message = ''): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Forbidden.';
        }
        $response = $this->getResponse($message);

        return $this->parentInstance($response, parent::HTTP_FORBIDDEN);
    }

    public function unauthorized(string $message = ''): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Unauthorized.';
        }
        $response = $this->getResponse($message);

        return $this->parentInstance($response, parent::HTTP_UNAUTHORIZED);
    }

    public function unprocessableEntity(string $message = '', array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Unprocessable entity.';
        }
        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function notFound(string $message = '', array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Not found.';
        }
        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_NOT_FOUND);
    }

    public function internalError(string $message = '', array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Internal error.';
        }

        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function methodNotAllowed(string $message, array $append = []): LumenJsonResponse
    {
        if (empty($message)) {
            $message = 'Method not allowed on this route.';
        }

        $response = $this->getResponse($message);

        if ( ! empty($append)) {
            $response = array_merge($response, $append);
        }

        return $this->parentInstance($response, parent::HTTP_METHOD_NOT_ALLOWED);
    }

    private function getResponse(string $message): array
    {
        return [
            'message' => $message
        ];
    }

    private function parentInstance(array $data, int $code): LumenJsonResponse
    {
        return new parent($data, $code);
    }
}
