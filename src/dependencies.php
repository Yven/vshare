<?php

// DIC configuration

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// CSRF Token
$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute('csrf_status', false);

        return $next($request, $response);
    });

    return $guard;
};

$container['newcookie'] = function ($c) {
    return new \Slim\Http\Cookies();
};

$container['cookie'] = function ($c) {
    return new \Slim\Http\Cookies($c->get('request')->getCookieParams());
};
