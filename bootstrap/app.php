<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(\Illuminate\Session\SessionManager::class, function () use ($app) {
    return $app->loadComponent(
        'session',
        Illuminate\Session\SessionServiceProvider::class, 'session'
    );
});

$app->singleton('session.store', function () use ($app) {
    return $app->loadComponent(
        'session',
        Illuminate\Session\SessionServiceProvider::class, 'session.store'
    );
});

$app->singleton(\App\Hospital\Domain\User\UserAuthorizationInterface::class, function () use ($app) {
    return new \App\Hospital\Application\User\UserAuthorization(
        $app->make(\App\Hospital\Domain\User\UserRepositoryInterface::class),
        $app->get('session')
    );
});

$app->bind(\App\Hospital\Domain\User\UserBuilderInterface::class, function () {
    return new \App\Hospital\Application\User\UserBuilder();
});

$app->bind(\App\Hospital\Domain\User\UserRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\UserRepository(
        $app->make(\App\Hospital\Domain\User\UserBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Doctor\DoctorBuilder();
});

$app->bind(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\DoctorRepository(
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface::class, function () {
    return new \App\Hospital\Domain\DoctorSchedule\DoctorScheduleBuilder();
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\DoctorScheduleRepository(
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\DoctorSchedule\ChooseDoctorSchedule(
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleClientInterface::class, function () use ($app) {
    return new \App\Hospital\Application\DoctorSchedule\ChooseDoctorScheduleClientUseCase(
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface::class)
    );
});

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/

$app->configure('app');
$app->configure('session');

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    \Illuminate\Session\Middleware\StartSession::class
]);


$app->routeMiddleware([
    'auth' => \App\Http\Middleware\AuthMiddleware::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
$app->register(\Illuminate\Redis\RedisServiceProvider::class);
$app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
