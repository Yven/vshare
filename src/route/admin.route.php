<?php


$app->group('/auth', function () {
    // login
    $this->post('', "\Src\Action\AdminAction:login");

    // csrf tokem
});

$app->get('/csrf', "\Src\Common:csrf");


$app->get('/test', function ($request, $response) {
    return $response->write("Csrf Success");
});


// admin operation
$app->group('/admin', function () {
    // get logged admin info
    $this->get('', "\Src\Action\AdminAction:getInfo");

    // signup
    $this->post('', "\Src\Action\AdminAction:signup");

    // modify admin info
    $this->put('/{id}', "\Src\Action\AdminAction:editInfo");
});
