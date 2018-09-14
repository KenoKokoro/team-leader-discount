<?php


namespace Discount\V1\Providers;


use Discount\V1\BLL\ServiceProvider as BllServiceProvider;
use Discount\V1\Modules\ServiceProvider as ModulesServiceProvider;
use Illuminate\Support\ServiceProvider;

class V1ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(BllServiceProvider::class);
    }
}