<?php


namespace Discount\V1\Client;


use App\External\Client\ClientInterface;
use App\External\Client\Exceptions\ServiceNotFound;
use App\External\Client\ResponseData;
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

    public function getByIds(string $service, array $ids, string $idName = 'id'): ResponseData
    {
        $file = $this->application->databasePath("stub/$service" . self::EXTENSION);
        $values = $this->jsonToArray($service, $file);
        $data = array_where($values, function(array $row) use ($ids, $idName) {
            return in_array($row[$idName], $ids);
        });

        return new ResponseData(['result' => $data, 'message' => 'Successfully executed.']);
    }

    public function findById(string $service, string $id, string $idName = 'id'): ResponseData
    {
        $file = $this->application->databasePath("stub/$service" . self::EXTENSION);
        $values = $this->jsonToArray($service, $file);
        $data = array_first($values, function(array $row) use ($id, $idName) {
            return $row[$idName] === $id;
        });

        return new ResponseData(['result' => $data, 'message' => 'Successfully executed.']);
    }

    /**
     * @param string $service
     * @param string $file
     * @return array
     * @throws ServiceNotFound
     */
    private function jsonToArray(string $service, string $file): array
    {
        if ( ! file_exists($file)) {
            throw new ServiceNotFound($service);
        }

        return json_decode(file_get_contents($file), true);
    }
}