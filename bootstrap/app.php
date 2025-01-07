<?php

use App\Http\Middleware\HandleCors;
use Illuminate\Foundation\Application;

$app = new Application(
    realpath(__DIR__.'/../') // Corrected this line to use __DIR__ properly
);

$app->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
);

// Register the HandleCors middleware here
$app->withMiddleware(function ($middleware) {
    $middleware->push(HandleCors::class); // Add CORS middleware globally
});

return $app;
