<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () {
    return view('authorization');
});

$router->group(['prefix' => 'profile'], function () use ($router) {
    $router->group(['prefix' => 'analyzes'], function () use ($router) {
        $router->get('/', ['as' => 'profile-analyzes', function () {
            return view('profile.analyzes.list');
        }]);

        $router->get('upload', ['as' => 'profile-analyzes-upload', function () {
            return view('profile.analyzes.upload');
        }]);
    });
});
