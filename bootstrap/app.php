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

$app->singleton(\App\Hospital\Domain\User\Interface\UserAuthorizationInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\User\UserAuthorization(
        $app->make(\App\Hospital\Domain\User\Interface\UserRepositoryInterface::class),
        $app->get('session')
    );
});

$app->bind(\App\Hospital\Domain\User\Interface\UserBuilderInterface::class, function () {
    return new \App\Hospital\Domain\User\UserBuilder();
});

$app->bind(\App\Hospital\Domain\User\Interface\UserRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\UserRepository(
        $app->make(\App\Hospital\Domain\User\Interface\UserBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Doctor\DoctorBuilder();
});

$app->bind(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\DoctorRepository(
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface::class),
        $app->make(\App\Hospital\Domain\User\Interface\UserRepositoryInterface::class)
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
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleListInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\DoctorSchedule\DoctorScheduleList(
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleClientInterface::class, function () use ($app) {
    return new \App\Hospital\Application\DoctorSchedule\DoctorScheduleClientUseCase(
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface::class),
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleListInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Department\DepartmentBuilder();
});

$app->bind(\App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\DepartmentRepository(
        $app->make(\App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\User\Interface\UserClientInterface::class, function () use ($app) {
    return new \App\Hospital\Application\User\UserClient(
        $app->make(\App\Hospital\Domain\User\Interface\UserRepositoryInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Client\Interface\ClientBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Client\ClientBuilder();
});

$app->bind(\App\Hospital\Domain\Client\Interface\ClientRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\ClientRepository(
        $app->make(\App\Hospital\Domain\Client\Interface\ClientBuilderInterface::class)
    );
});

$app->bind('telegram.bot', function () {
    return new SergiX44\Nutgram\Nutgram(config('telegram.bot.token'));
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\MessangerInterface::class, function () {
    return new \App\Hospital\Domain\Messanger\Messanger();
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\MessangerHandlerRepositoryInterface::class, function () {
    return new \App\Hospital\Infrastructure\Repository\MessangerHandlerRepository();
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\MessangerManagerInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Messanger\TelegramMessangerManager(
        $app->make(\App\Hospital\Domain\Client\Interface\ClientRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Client\Interface\ClientBuilderInterface::class),
        $app->make(\App\Hospital\Domain\Messanger\Interface\MessangerInterface::class),
        $app->make('telegram.bot'),
        $app->make(\App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface::class),
        $app->make(\App\Hospital\Domain\Messanger\Interface\MessangerHandlerRepositoryInterface::class),
        $app->make(\Psr\Log\LoggerInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\Keyboard\KeyboardBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Messanger\Keyboard\KeyboardBuilder();
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Messanger\KeyboardButton\KeyboardButtonBuilder();
});

$app->bind(\App\Hospital\Domain\Messanger\Interface\KeyboardButton\KeyboardButtonCallbackBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Messanger\KeyboardButton\KeyboardButtonCallbackBuilder();
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface::class, function () {
    return new \App\Hospital\Domain\Appointment\AppointmentBuilder();
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface::class, function () use ($app) {
    return new \App\Hospital\Infrastructure\Repository\AppointmentRepository(
        $app->make(\App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\AppointmentListInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\Appointment\AppointmentList(
        $app->make(\App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\CancelAppointmentInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\Appointment\CancelAppointment(
        $app->make(\App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface::class, function () {
    return new \App\Hospital\Infrastructure\Repository\MakeAppointmentRepository(
        \Illuminate\Support\Facades\Redis::client()
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\Appointment\MakeAppointment(
        $app->make(\App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface::class),
        $app->make(\App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface::class)
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentRepositoryInterface::class, function () {
    return new \App\Hospital\Infrastructure\Repository\ReMakeAppointmentRepository(
        \Illuminate\Support\Facades\Redis::client()
    );
});

$app->bind(\App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface::class, function () use ($app) {
    return new \App\Hospital\Domain\Appointment\ReMakeAppointment(
        $app->make(\App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface::class),
        $app->make(\App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentRepositoryInterface::class)
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
$app->configure('telegram');

$app->setLocale(config('app.locale'));

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
$app->register(\Thedevsaddam\LumenRouteList\LumenRouteListServiceProvider::class);

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
