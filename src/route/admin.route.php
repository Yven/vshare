<?php


$app->group('/auth', function () {
    // login
    $this->post('', "\Src\Action\AdminAction:login");
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
