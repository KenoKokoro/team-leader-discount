<?php


namespace Discount\V1\BLL\Discount;


use App\External\Client\ClientInterface;
use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Contracts\DiscountCalculation;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Illuminate\Contracts\Support\Arrayable;

class DiscountLayer implements DiscountInterface
{
    /**
     * @var DiscountCalculation
     */
    private $calculator;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var GenericFactoryInterface
     */
    private $generic;

    public function __construct(
        DiscountCalculation $calculator,
        ClientInterface $client,
        GenericFactoryInterface $generic
    ) {
        $this->calculator = $calculator;
        $this->client = $client;
        $this->generic = $generic;
    }

    public function calculationRules(): array
    {
        return [
            'customer-id' => 'required',
            'total' => 'required',
            'items' => 'required|array',
            'items.*.product-id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.unit-price' => 'required',
            'items.*.total' => 'required',
        ];
    }

    public function calculateFor(array $order): Arrayable
    {
        $order['customer'] = $this->client->findById('customers', $order['customer-id'])->result();
        $order['products'] = $this->client->getByIds('products', array_pluck($order['items'], 'product-id'))->result();
        /** @var OrderData $orderData */
        $orderData = $this->generic->make(OrderData::class, $order);

        return $this->calculator->calculate($orderData);
    }
}