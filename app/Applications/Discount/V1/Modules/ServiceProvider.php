<?php


namespace Discount\V1\Modules;


use Discount\V1\Modules\Calculator\ServiceProvider as CalculatorServiceProvider;
use Discount\V1\Modules\Client\ServiceProvider as ClientServiceProvider;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->register(CalculatorServiceProvider::class);
        $this->app->register(ClientServiceProvider::class);
    }
}