<?php


namespace Discount\V1\Modules\Calculator\Contracts;


interface RuleFactoryInterface
{
    /**
     * Create the rule instance
     * @param string $className
     * @return mixed
     */
    public function make(string $className): DiscountRule;
}