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

$router->group(['prefix' => 'profile'], function () use ($router) {
    $router->get('/', ['as' => 'profile', 'middleware' => ['auth'], function () {
        return view('profile.profile');
    }]);

    $router->group(['prefix' => 'schedule'], function () use ($router) {
        $router->get('/', ['as' => 'profile-schedule', function () {
            return view('profile.schedule.list');
        }]);

        $router->get('choose', ['as' => 'profile-schedule-choose', 'middleware' => ['auth'], function () {
            return view('profile.schedule.choose');
        }]);
    });
});

$router->group(['prefix' => 'tg'], function () use ($router) {
    $router->post('/Bot', ['as' => 'telegram.bot', 'uses' => 'TelegramBotController']);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'v1'], function () use ($router) {
        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->post('auth', ['uses' => 'UserController@authorization']);

            $router->post('search', ['uses' => 'UserController@searchByName']);
        });

        $router->group(['prefix' => 'schedule'], function () use ($router) {
            $router->get('/', ['uses' => 'DoctorScheduleController@getDoctorSchedule']);

            $router->get('busy', ['uses' => 'DoctorScheduleController@getBusyDates']);

            $router->post('choose', ['uses' => 'DoctorScheduleController@chooseDates']);
        });
    });
});

$router->group(['prefix' => 'telegram'], function () use ($router) {
    $router->post('appointment-bot', ['uses' => 'TelegramController']);
});

$router->get('/login', ['as' => 'login', 'uses' => 'UserController@login']);

$router->get('/', ['as' => 'home', function () {
    return view('home');
}]);
