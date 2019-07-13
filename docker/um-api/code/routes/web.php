<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Group;

$router->group(['middleware' => 'jwt.auth:' . Group::SUPER_ADMIN], function () use ($router) {
        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->get('', 'UserController@index');
            $router->post('create', 'UserController@create');
            $router->put('edit/{id}', 'UserController@update');
            $router->get('{id}', 'UserController@show');
            $router->delete('delete/{id}', 'UserController@delete');
            $router->post('assign-to-group', 'UserController@assignUserToGroup');
            $router->post('remove-from-group', 'UserController@removeUserFromGroup');
        });

        $router->group(['prefix' => 'group'], function () use ($router) {
            $router->get('', 'GroupController@index');
            $router->post('create', 'GroupController@create');
            $router->put('edit/{id}', 'GroupController@update');
            $router->get('{id}', 'GroupController@show');
            $router->delete('delete/{id}', 'GroupController@delete');
        });
});

$router->post('/user/authenticate', 'AuthenticateController@authenticate');
