<?php


namespace App\External\Client;


class ResponseData
{
    private $result = [];

    public function __construct(array $data)
    {
        $this->result = $data['result'] ?? [];
    }

    public function result(): array
    {
        return $this->result;
    }
}