<?php


namespace Discount\V1\Modules\Calculator;


use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Contracts\DiscountRule;
use Discount\V1\Modules\Calculator\Contracts\RuleFactoryInterface;

class RuleFactory implements RuleFactoryInterface
{
    /**
     * @var GenericFactoryInterface
     */
    private $generic;

    public function __construct(GenericFactoryInterface $generic)
    {
        $this->generic = $generic;
    }

    public function make(string $className): DiscountRule
    {
        return new $className($this->generic);
    }
}