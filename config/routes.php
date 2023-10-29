<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
// Router::addRoute(['GET'], '/products', 'App\Controller\ProductionController@index');
// Router::addRoute(['GET'], '/products/{id}', 'App\Controller\ProductionController@show');
// Router::addRoute(['POST'], '/products/{id}', 'App\Controller\ProductionController@create');

Router::addGroup('/api/v1',function (){
    Router::get('/products', 'App\Controller\ProductionController@index');
    Router::get('/products/{id}', 'App\Controller\ProductionController@show');
    Router::post('/products', 'App\Controller\ProductionController@create');
    Router::put('/products/{id}', 'App\Controller\ProductionController@update');
    Router::delete('/products/{id}', 'App\Controller\ProductionController@delete');

    //Inventory
    Router::post('/inventory/addInventory', 'App\Controller\InventoryController@addInventory');
    Router::post('/inventory/reduceInventory', 'App\Controller\InventoryController@reduceInventory');
});

Router::get('/favicon.ico', function () {
    return '';
});
