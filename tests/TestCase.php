<?php

namespace Tests;

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
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function mockInstance(string $instance): MockInterface
    {
        $mock = m::mock($instance);
        app()->bind($instance, $mock);

        return $mock;
    }
}
