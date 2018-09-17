<?php


namespace Discount\V1\Modules\Calculator;


use Discount\V1\Modules\Calculator\Contracts\DiscountCalculation;
use Discount\V1\Modules\Calculator\Contracts\RuleFactoryInterface;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DiscountCalculation::class, DiscountRuleCalculator::class);
        $this->app->bind(RuleFactoryInterface::class, RuleFactory::class);
    }
}