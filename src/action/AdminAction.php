<?php

namespace Src\Action;

use \Src\Model\Admin;


class AdminAction extends BaseAction
{
    private $_admin;


    public function __construct(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response
    ) {
        parent::__construct($request, $response);
        $this->_admin = new Admin();
    }

    public function save(){
        $data = $this->_request->getParsedBody();
        $data['passwd'] = password_hash($data['passwd'], PASSWORD_DEFAULT);
        $query = $this->_admin->vInsertInto()->values($data)->execute();
        if ($query) {
            $res = $this->_admin->vFrom()->where("id", $query);
            foreach ($res as $value) {
                unset($value["passwd"]);
            }
            return $this->successResponse($value);
        } else {
            return $this->errorResponse(422);
        }
    }

    /**
     * 登录检测
     * @return Response
     */
    public function login()
    {
        $data = $this->_request->getParsedBody();
        if (isset($data['username']) && !empty($data['username'])) {
            $res = $this->_admin->from()->where("username", $data['username'])->fetch();
            if (!empty($res)) {
                if (isset($data['passwd']) && !empty($data['passwd'])) {
                    if (password_verify($data['passwd'], $res["passwd"])) {
                        unset($res["passwd"]);
                        return $this->successResponse($res);
                    }
                    return $this->successResponse(["message" => "nothing happend"]);
                }
            }
        }
    //         throw new \Exception("Empty entity", 400);
    //     try {
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getCode(), $e->getMessage());
    //     }
    }
}
