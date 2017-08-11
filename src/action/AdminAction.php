<?php

namespace Src\Action;

use Src\Model\Admin;

class AdminAction extends BaseAction
{
    private $_admin;

    protected $_container;

    public function __construct(\Slim\Container $container)
    {
        $this->_container = $container;
        $this->_admin = new Admin();
    }

    public function signup($request, $response, $args)
    {
    }

    /**
     * login.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
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
            return $this->error(
                $status['code'],
                empty($status['message']) ? $this->_ERR_MSG[$status['code']] : $status['message']
            );
        } else {
            $this->cookie($this->_container['newcookie'], ['token' => $this->_admin->getJWT()]);

            return $this->success($res);
        }
    }

    /**
     * get logged admin info.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function getInfo($request, $response, $args)
    {
        // init the parent class
        parent::__construct($response);
        // get info
        $res = $this->_admin->getInfo($this->_container['cookie']->get('token'));

        $status = $this->_admin->getStatus();
        if (is_null($res)) {
            return $this->error(
                $status['code'],
                empty($status['message']) ? $this->_ERR_MSG[$status['code']] : $status['message']
            );
        } else {
            return $this->success($res);
        }
    }
}
