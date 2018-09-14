<?php


namespace Discount;


use Discount\V1\Providers\RouteServiceProvider as V1RouteServiceProvider;
use Illuminate\Support\ServiceProvider;

class DiscountServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->v1();
    }

    private function v1(): void
    {
        $this->app->register(V1RouteServiceProvider::class);
    }
}