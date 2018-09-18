<?php


namespace Discount\V1\Providers;


use Discount\V1\BLL\ServiceProvider as BllServiceProvider;
use Discount\V1\Http\Middleware\Authenticated;
use Discount\V1\Modules\ServiceProvider as ModulesServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;

class V1ServiceProvider extends ServiceProvider
{
    /**
     * @var Application
     */
    protected $app;

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(BllServiceProvider::class);

        $this->authMiddleware();
    }

    private function authMiddleware(): void
    {
        $this->app->routeMiddleware(['auth' => Authenticated::class]);
    }
}