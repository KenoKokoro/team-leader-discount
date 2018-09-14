<?php


namespace Discount\V1\Providers;


use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var Application
     */
    protected $app;

    public function boot(): void
    {
        $this->app->router->group(['prefix' => 'api/v1'], function($router) {
            require __DIR__ . '/../api.php';
        });
    }
}