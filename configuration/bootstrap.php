<?php

namespace PanthorApplication\Bootstrap;

use PanthorApplication\CachedContainer;
use QL\Panthor\Bootstrap\Di;

$root = __DIR__ . '/..';
require_once $root . '/vendor/autoload.php';

return Di::getDi($root, CachedContainer::class);
