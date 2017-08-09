<?php


$app->group('/auth', function () {
    // login
    $this->post('', "\Src\Action\AdminAction:login");

    // get admin info
    $this->get('', "\Src\Action\AdminAction:getInfo");

    // logout
    $this->delete('', "\Src\Action\AdminAction:logout");
});

// admin operation
$app->group('/admin', function () {
});
