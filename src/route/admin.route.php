<?php


$app->group("/auth", function () {
    // login
    $this->post("", function ($request, $response) {
        $admin = new \Src\Action\AdminAction($request, $response);
        return $admin->login();
    });

    // logout
    $this->delete("", function ($request, $response) {
        $response->getBody()->write("you want to logout");
    });
});

// admin operation
$app->group("/admin", function () {
});
