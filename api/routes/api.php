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
    $router->get('/', function () {
        return response()->json(['version' => '1.0'], 200);
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('rooms', 'RoomController@index');
        $router->get('rooms/{id}', 'RoomController@show');

        $router->get('users', 'UserController@index');
        $router->get('users/{id}', 'UserController@show');

        $router->get('keys', 'KeyController@index');
        $router->get('keys/{id}', 'KeyController@show');

        $router->group(['middleware' => 'admin'], function () use ($router) {
            $router->post('rooms', 'RoomController@store');
            $router->put('rooms/{id}', 'RoomController@update');
            $router->delete('rooms/{id}', 'RoomController@delete');

            $router->post('users', 'UserController@store');
            $router->put('users/{id}', 'UserController@update');
            $router->delete('users/{id}', 'UserController@delete');

            $router->post('keys', 'KeyController@store');
            $router->put('keys/{id}', 'KeyController@update');
            $router->delete('keys/{id}', 'KeyController@delete');
        });
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('profile', 'AuthController@profile');    
            $router->post('change-password', 'AuthController@changePassword');
            $router->post('logout', 'AuthController@logout');
        });
    });
});