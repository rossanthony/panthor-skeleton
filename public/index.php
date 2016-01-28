<?php

namespace PanthorApplication\Bootstrap;

if (!$container = @include __DIR__ . '/../configuration/bootstrap.php') {
    http_response_code(500);
    echo "The application failed to start.\n";
    exit;
};

// Enable error handler first
$handler = $container->get('error.handler');
$handler->register();
ini_set('display_errors', 0);

$app = $container->get('slim');

// Attach error handler to Slim.
$handler->attach($app);

// Start app
$app->run();
