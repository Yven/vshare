<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    // $request = $this->csrf->generateNewToken($request);

    $nameKey = $this->csrf->getTokenNameKey();
    $valueKey = $this->csrf->getTokenValueKey();
    $name = $request->getAttribute($nameKey);
    $value = $request->getAttribute($valueKey);
    // $this->logger->info("Slim-Skeleton '/' route");

    $tokenArray = [
        $nameKey => $name,
        $valueKey => $value
    ];

    return $response->write(json_encode($tokenArray));
})->add($container->get('csrf'));
