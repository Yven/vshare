<?php

// use \Psr\Http\Message\ServerRequestInterface as Request;
// use \Psr\Http\Message\ResponseInterface as Response;

// require __DIR__ . '/../vendor/autoload.php';

// $settings = require __DIR__ . '/../src/settings.php';
// $app = new \Slim\App($settings);

// $app->get('/test', function (Request $request, Response $response) {
//     // $response->getBody()->write("Hello");
//     return $response->withHeader("Content-Type", "application/json");
// });

// $app->run();

class test {
    public function __construct(){
        $r = new \ReflectionClass($this);
        echo $r->getShortName();
    }
}

class test2 extends test{
    public function __construct(){
        parent::__construct();
    }
}

$test2 = new test2();
