<?php

namespace Src\Action;

use Src\Model\Admin;
use Src\Config;

class AdminAction extends BaseAction
{
    private $_admin;

    protected $_container;

    public function __construct(\Slim\Container $container) {
        $this->_container = $container;
        $this->_admin = new Admin();
    }

    public function signup()
    {
    }

    /**
     * 登录检测.
     *
     * @return Response
     */
    public function login($request, $response, $args)
    {
        // init the parent class
        parent::__construct($response);
        // check the user's info
        $res = $this->_admin->login($request->getParsedBody());

        // get result status
        $status = $this->_admin->getStatus();
        if (is_null($res)) {
            return $this->errorResponse(
                $status['code'],
                empty($status['message']) ? $this->_ERR_MSG[$status['code']] : $status['message']
            );
        } else {
            return $this->successResponse($res);
        }

    }

    /**
     * get the admin info that has been logined.
     *
     * @return Response
     */
    public function getInfo($request, $response, $args)
    {
        // init the parent class
        parent::__construct($response);
        // get info
        $res = $this->_admin->getInfo($_COOKIE['token']);

        $status = $this->_admin->getStatus();
        if (is_null($res)) {
            return $this->errorResponse(
                $status['code'],
                empty($status['message']) ? $this->_ERR_MSG[$status['code']] : $status['message']
            );
        } else {
            return $this->successResponse($res);
        }
    }

    /**
     * admin logout
     *
     * @return Rsponse
     */
    public function logout () {
        if (isset($_COOKIE['token'])) {
            unset($_COOKIE['token']);
        }
    }
}
