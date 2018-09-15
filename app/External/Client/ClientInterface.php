<?php


namespace App\External\Client;


use App\External\Client\Exceptions\ServiceNotFound;

interface ClientInterface
{
    /**
     * Call external API to read multiple records by some unique ID
     * @param string $service (customers|products)
     * @param array  $ids
     * @param string $idName
     * @return ResponseData
     * @throws ServiceNotFound
     */
    public function getByIds(string $service, array $ids, string $idName = 'id'): ResponseData;

    /**
     * Call external API to read single record by some unique ID
     * @param string $service (customers|products)
     * @param string $id
     * @param string $idName
     * @return ResponseData
     * @throws ServiceNotFound
     */
    public function findById(string $service, string $id, string $idName = 'id'): ResponseData;
}