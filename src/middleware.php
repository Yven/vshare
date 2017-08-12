<?php
// Application middleware

// add csrf middleware
$app->add($container->get('csrf'));

// TODO
// validate code
// identity validate
// root validate

// $app->add(function ($request, $response, $next) {
// });
