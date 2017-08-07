<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

$container = $app->getContainer();
// $container['csrf'] = function ($c){
    // return new \Slim\Csrf\Guard;
// };
// $app->add($container->get('csrf'));

// $container['logger'] = function($c){
//     $logger = new \Monolog\Logger("my_logger");
//     $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
//     $logger->pushHandler($file_handler);
//     return $logger;
// };

// $container['db'] = function($c){
//     $db = $c['settings']['db'];
//     $pdo = new PDO("mysql:host=".$db['host'].";dbname=".$db['dbname'], $db['user'], $db['pass']);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//     return $pdo;
// };

$app->get('/', function (Request $request, Response $response) {
    // $name = $request->getAttribute('name');
    // $data = $request->getQueryParams();
    // $response->getBody()->write("Hello, $name");
    // $this->logger->info("this is a test");
    // foreach ($this->db->query("select * from v_admin") as $value) {
    // }
});

// $app->post('/hello/post/', function (Request $request, Response $response) {
    // $data = $request->getParsedBody();
    // $response->getBody()->write();
// });

$app->run();
