<?php


namespace Discount\V1\Modules\Client;


use App\External\Client\ClientInterface;
use Discount\V1\Client\StubClient;
use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, StubClient::class);
    }
}