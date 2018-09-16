<?php


namespace Discount\V1\Modules\Calculator;


use App\Generics\GenericFactoryInterface;
use Discount\V1\Modules\Calculator\Contracts\DiscountCalculation;
use Discount\V1\Modules\Calculator\Contracts\DiscountRule;
use Discount\V1\Modules\Calculator\Data\DiscountData;
use Discount\V1\Modules\Calculator\Data\OrderData;
use Discount\V1\Modules\Calculator\Exceptions\NotEligibleForDiscount;
use Discount\V1\Modules\Calculator\Rules\CustomerReachedRevenue;
use Discount\V1\Modules\Calculator\Rules\SwitchesCategorySixthFree;

class DiscountRuleCalculator implements DiscountCalculation
{
    /**
     * @var GenericFactoryInterface
     */
    private $generic;

    public function __construct(GenericFactoryInterface $generic)
    {
        $this->generic = $generic;
    }

    private $rulesByPriority = [
        CustomerReachedRevenue::class,
        SwitchesCategorySixthFree::class
    ];

    public function calculate(OrderData $order): DiscountData
    {
        $matchingRule = $this->matchRule($order);

        if (is_null($matchingRule)) {
            throw new NotEligibleForDiscount($order);
        }

        return $matchingRule->discount($order);
    }

    /**
     * Check if any of the rules are matching some kind of rule that are predefined above
     * @param OrderData $order
     * @return DiscountRule|null
     */
    private function matchRule(OrderData $order): ?DiscountRule
    {
        foreach ($this->rulesByPriority as $className) {
            $instance = $this->createRuleInstance($className);

            if ($instance->match($order)) {
                return $instance;
            }
        }

        return null;
    }

    /**
     * Create the rule instance
     * @param string $className
     * @return DiscountRule
     */
    private function createRuleInstance(string $className): DiscountRule
    {
        /** @var DiscountRule $instance */
        $instance = new $className($this->generic);

        return $instance;
    }
}