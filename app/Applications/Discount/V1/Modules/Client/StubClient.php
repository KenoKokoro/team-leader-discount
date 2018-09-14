<?php


namespace Discount\V1\Client;


use App\External\Client\ClientInterface;
use Laravel\Lumen\Application;

class StubClient implements ClientInterface
{
    const EXTENSION = '.json';

    /**
     * @var Application
     */
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getByIds(string $service, array $ids, string $idName = 'id'): array
    {
        $file = $this->application->databasePath("stub/$service" . self::EXTENSION);
        return [];
    }

    public function findById(string $service, string $value, string $idName = 'id'): array
    {
        return [];
    }
}