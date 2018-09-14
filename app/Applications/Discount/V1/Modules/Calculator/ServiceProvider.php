<?php


namespace Discount\V1\Modules\Calculator;


use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DiscountCalculation::class, DiscountCalculationImpl::class);
    }
}