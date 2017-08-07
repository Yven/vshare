<?php
// Application middleware

// $checkAuth = function ($request, $response, $next) {
//     $nameKey = $this->csrf->getTokenNameKey();
//     $valueKey = $this->csrf->getTokenValueKey();
//     $name = $request->getAttribute($nameKey);
//     $value = $request->getAttribute($valueKey);
// };

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(function($request, $response, $next){

    // $response->getBody()->write("before");
    $next($request, $response);
    // $response->getBody()->write("after");

    return $response;
});
