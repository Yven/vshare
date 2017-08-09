<?php

// use \Psr\Http\Message\ServerRequestInterface as Request;
// use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

// $settings = require __DIR__ . '/../src/settings.php';
// $app = new \Slim\App($settings);

// $app->get('/test', function (Request $request, Response $response) {
//     // $response->getBody()->write("Hello");
//     return $response->withHeader("Content-Type", "application/json");
// });

// $app->run();


use \Firebase\JWT\JWT;

$key = "screct";
$token = array(
    "iss" => "http://vshare.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
);

$jwt = JWT::encode($token, $key);
$decoded = JWT::decode($jwt, $key, array('HS256'));

print_r($jwt);

