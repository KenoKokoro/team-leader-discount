<?php


namespace Discount\V1\BLL;


use Discount\V1\BLL\Discount\DiscountInterface;
use Discount\V1\BLL\Discount\DiscountLayer;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DiscountInterface::class, DiscountLayer::class);
    }
}