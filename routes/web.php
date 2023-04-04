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

$router->get('/login', ['as' => 'login', 'uses' => 'UserController@login']);

$router->group(['prefix' => 'profile'], function () use ($router) {
    $router->get('/', ['as' => 'profile', 'middleware' => ['auth'], function () {
        return view('profile.profile');
    }]);

    $router->group(['prefix' => 'analyze'], function () use ($router) {
        $router->get('/', ['as' => 'profile-analyze', 'middleware' => ['auth'], function () {
            return view('profile.analyze.list');
        }]);

        $router->get('upload', ['as' => 'profile-analyze-upload', 'middleware' => ['auth'], function () {
            return view('profile.analyze.upload');
        }]);
    });

    $router->group(['prefix' => 'schedule'], function () use ($router) {
        $router->get('/', ['as' => 'profile-schedule', function () {
            return view('profile.schedule.list');
        }]);

        $router->get('choose', ['as' => 'profile-schedule-choose', 'middleware' => ['auth'], function () {
            return view('profile.schedule.choose');
        }]);
    });
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->post('auth', ['uses' => 'UserController@authorization']);
        });
    });
});

$router->get('/', function () {
    return 'Hello world!!!';
});
