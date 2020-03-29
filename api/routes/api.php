<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('rooms', 'RoomController@index');
        $router->get('rooms/{id}', 'RoomController@show');
        $router->post('rooms', 'RoomController@store');
        $router->put('rooms/{id}', 'RoomController@update');
        $router->delete('rooms/{id}', 'RoomController@delete');
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->post('logout', 'AuthController@logout');
    });
});