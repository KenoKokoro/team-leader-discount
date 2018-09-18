<?php

namespace Tests;

use Illuminate\Http\Request;
use Laravel\Lumen\Testing\TestCase as LumenTestCase;
use Mockery as m;
use Mockery\MockInterface;

abstract class TestCase extends LumenTestCase
{
    /**
     * Creates the application.
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $uri = $app->make('config')->get('app.url', 'http://localhost');

        $components = parse_url($uri);

        $server = $_SERVER;

        if (isset($components['path'])) {
            $server = array_merge($server, [
                'SCRIPT_FILENAME' => $components['path'],
                'SCRIPT_NAME' => $components['path'],
            ]);
        }

        $app->instance('request', Request::create(
            $uri, 'GET', [], [], [], $server
        ));

        return $app;
    }

    public function mockInstance(string $instance): MockInterface
    {
        $mock = m::mock($instance);
        app()->instance($instance, $mock);

        return $mock;
    }
}
