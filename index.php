<?php

define('MINIMUM_PHP', '8.1.0');

if (version_compare(PHP_VERSION, MINIMUM_PHP, '<')) {
    die('Sorry, your PHP version is not supported. The minimum PHP version is 8.1.');
}

/**
 * Constant that is checked in included files to prevent direct access.
 */
\define('_PREVENT', 1);

\define('PATH_ROOT', __DIR__);

if (!file_exists(PATH_ROOT . '/app/configuration.php')) {
    echo 'No configuration file found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/configuration.php';

if (!file_exists(PATH_ROOT . '/app/cashe/autoload_psr4.php')) {
    echo 'Namespaces file not found. Exiting...';
    exit;
}
$map = require_once PATH_ROOT . '/app/cashe/autoload_psr4.php';

if (!file_exists(PATH_ROOT . '/app/autoload.php')) {
    echo 'Autoload file not found. Exiting...';
    exit;
}
require_once PATH_ROOT . '/app/autoload.php';

if (file_exists(PATH_ROOT . '/vendor/autoload.php')) {
    require_once PATH_ROOT . '/vendor/autoload.php';
}

$current = \Blogy\Helper\RouteHelper::getPage();

if ($current['page'] == '404') {
    http_response_code(404);
    echo '404 - Page not found.';
    exit;
}

$ControllerClassName = '\\Blogy\\Controller\\' . $current['page'] . 'Controller';
$task = $current['task'] ?: 'display';

try {
    $controller = new $ControllerClassName($current);

    if (method_exists($controller, $task)) {
        $controller->$task();
    } else {
        echo 'Method not found. Exiting...';
        exit;
    }
} catch (\Exception $e) {
    //echo $e->getMessage();
}
