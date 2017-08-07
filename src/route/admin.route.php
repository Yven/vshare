<?php

// admin operation
$app->group("/admin", function () {
});

$app->group("/auth", function () {
    // login
    $this->post("", function ($request, $response) {
        // $data = $request->getParsedBody();
        $admin = new \Src\Action\AdminAction($request, $response);
        return $admin->save();
        // $response->getBody()->write($request->getParsedBody()['username']);
    });

    // logout
    $this->delete("", function ($request, $response) {
        $response->getBody()->write("you want to logout");
    });
});
