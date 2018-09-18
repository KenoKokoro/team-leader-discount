<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Discount\V1\Http\Controllers\DiscountsController;

$router->post('discounts/calculate',
    ['as' => 'discounts.calculate', 'uses' => DiscountsController::class . '@calculate']);
