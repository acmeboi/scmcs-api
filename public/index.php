<?php

use App\Kernel;

// Load polyfill for PHP 8.4 functions (array_any) for PHP 8.2 compatibility
require_once dirname(__DIR__).'/src/polyfill.php';

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
