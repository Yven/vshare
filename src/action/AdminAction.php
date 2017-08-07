<?php

namespace Src\Action;

use \Src\Model\Admin;


class AdminAction extends BaseAction
{
    private $admin;


    public function __construct(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        parent::__construct($request, $response);
        $this->admin = new Admin();
    }

    public function save(){
        $data = $this->_request->getParsedBody();
        $data['passwd'] = password_hash($data['passwd'], PASSWORD_DEFAULT);
        $query = $this->admin->vInsertInto()->values($data)->execute();
        if ($query) {
            $res = $this->admin->vFrom()->where("id", $query);
            foreach ($res as $value) {
                unset($value["passwd"]);
                // return $this->successResponse($value);
                return $this->successResponse($value, ["Content-Type" => "application/json;charset=utf-8"]);
            }
        } else {
            return $this->errorResponse(422);
        }
    }

    public function checkLogin()
    {
    }
}
