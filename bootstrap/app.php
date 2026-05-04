<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Load environment variables early so config files can read env() values.
$envFile = $app->basePath('.env');
if (file_exists($envFile)) {
    Dotenv\Dotenv::createImmutable($app->basePath())->safeLoad();
}

// Bootstrap a config repository before any service providers are registered.
$configRepository = new Illuminate\Config\Repository;
$configPath = $app->basePath('config');

foreach (glob($configPath . '/*.php') as $configFile) {
    $configRepository->set(basename($configFile, '.php'), require $configFile);
}

$app->instance('config', $configRepository);

// FoundationServiceProvider expects the filesystem service during bootstrap.
$app->singleton('files', function () {
    return new Illuminate\Filesystem\Filesystem;
});

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The HTTP kernel and the
| Console kernel are the primary ones for this application.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Register core service providers
$app->register(Illuminate\Events\EventServiceProvider::class);
$app->register(Illuminate\Database\DatabaseServiceProvider::class);
$app->register(Illuminate\View\ViewServiceProvider::class);
$app->register(Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class);
$app->register(Illuminate\Foundation\Providers\FoundationServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
