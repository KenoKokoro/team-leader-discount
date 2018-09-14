<?php


namespace App\External\Client;


interface ClientInterface
{
    /**
     * Call external API to read multiple records by some unique ID
     * @param string $service (customers|products)
     * @param array  $ids
     * @param string $idName
     * @return array
     */
    public function getByIds(string $service, array $ids, string $idName = 'id'): array;

    /**
     * Call external API to read single record by some unique ID
     * @param string $service (customers|products)
     * @param string $value
     * @param string $idName
     * @return array
     */
    public function findById(string $service, string $value, string $idName = 'id'): array;
}