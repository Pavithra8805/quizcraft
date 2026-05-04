<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First, we need to get an application instance. This is pulled from the
| bootstrap/app.php file. Next, we will bind "web" exception to give us
| the ability to catch errors & display them properly for the user.
|
*/

$app = require __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
