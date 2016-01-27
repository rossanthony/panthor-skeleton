<?php

namespace PanthorApplication\Bootstrap;

if (!$container = @include __DIR__ . '/../configuration/bootstrap.php') {
    http_response_code(500);
    echo "The application failed to start.\n";
    exit;
};

$container->get('slim')->run();
