<?php


namespace Discount\V1\Providers;


use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use Laravel\Lumen\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var Application
     */
    protected $app;

    public function register(): void
    {
        $this->app->router->group(['prefix' => 'api/v1', 'as' => 'v1', 'middleware' => ['auth']], function ($router) {
            require __DIR__ . '/../api.php';
        });
        $this->app->router->get('api/v1/docs', function (Router $router) {
            return view('v1.docs');
        });
    }
}