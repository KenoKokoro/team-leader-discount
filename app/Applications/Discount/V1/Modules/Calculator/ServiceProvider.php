<?php


namespace Discount\V1\Modules\Calculator;


use Discount\V1\Modules\Calculator\Contracts\DiscountCalculation;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DiscountCalculation::class, DiscountRuleCalculator::class);
    }
}