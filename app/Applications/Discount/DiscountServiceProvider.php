<?php


namespace Discount;


use Discount\V1\Providers\V1ServiceProvider;
use Illuminate\Support\ServiceProvider;

class DiscountServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->v1();
    }

    private function v1(): void
    {
        $this->app->register(V1ServiceProvider::class);
    }
}