<?php


namespace Discount\V1\Modules\Calculator\Rules;


use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Contracts\DiscountRule;
use Discount\V1\Modules\Calculator\Data\DiscountData;

abstract class BaseRule implements DiscountRule
{
    /**
     * @var GenericFactoryInterface
     */
    private $generic;

    /**
     * Determines if there is a match in the rule or not
     * @var bool
     */
    protected $hasMatch = false;

    public function __construct(GenericFactoryInterface $generic)
    {
        $this->generic = $generic;
    }

    /**
     * Make instance of the discount data and return it
     * @param array $attributes
     * @return DiscountData
     */
    protected function data(array $attributes): DiscountData
    {
        /** @var DiscountData $data */
        $data = $this->generic->make(DiscountData::class, $attributes);

        return $data;
    }
}