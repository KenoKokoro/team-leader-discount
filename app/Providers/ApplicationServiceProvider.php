<?php


namespace App\Providers;


use App\Generics\GenericFactory;
use App\Generics\GenericFactoryInterface;
use Discount\DiscountServiceProvider;
use Illuminate\Support\ServiceProvider;

class ApplicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(GenericFactoryInterface::class, GenericFactory::class);

        $this->app->register(DiscountServiceProvider::class);
    }
}