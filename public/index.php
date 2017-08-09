<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__.$url['path'];
    if (is_file($file)) {
        return false;
    }
}

define('SETTING_PATH', __DIR__.'/../src/settings.php');

require __DIR__.'/../vendor/autoload.php';

session_start();

// Instantiate the app
require __DIR__.'/../src/Config.php';
\Src\Config::load(SETTING_PATH);
$app = new \Slim\App(\Src\Config::get());

// Set up dependencies
require __DIR__.'/../src/dependencies.php';

// Register middleware
// require __DIR__ . '/../src/middleware.php';

// model and action
foreach (glob(__DIR__.'/../src/model/*.php') as $path) {
    require_once $path;
}
foreach (glob(__DIR__.'/../src/action/Base*.php') as $path) {
    require_once $path;
}
foreach (glob(__DIR__.'/../src/action/*.php') as $path) {
    require_once $path;
}

// Register routes
require __DIR__.'/../src/route/admin.route.php';

// Run app
$app->run();
