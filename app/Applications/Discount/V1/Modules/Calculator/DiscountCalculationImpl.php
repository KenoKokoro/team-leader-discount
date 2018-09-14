<?php


namespace Discount\V1\Modules\Calculator;


use App\External\Client\ClientInterface;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;

class DiscountCalculationImpl implements DiscountCalculation
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param OrderData $items
     * @return DiscountData
     */
    public function calculate(OrderData $items): DiscountData
    {
        return new DiscountData();
    }
}