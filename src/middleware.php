<?php
// Application middleware

// add csrf middleware
$app->add($container->get('csrf'));

// $app->add(function ($request, $response, $next) {
// });
