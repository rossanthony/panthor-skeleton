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
$handler->registerShutdown();
ini_set('display_errors', 0);

$app = $container->get('slim');
$routes = $container->get('router.loader');

$routes($app);

// Attach slim to exception handler for error rendering.
$container
    ->get('exception.handler')
    ->attachSlim($app);

// Uncomment to enable encrypted cookies
// $encryptedCookies = $container->get('panthor.middleware.encrypted_cookies');
// $app->add($encryptedCookies);

// Start app
$app->run();
