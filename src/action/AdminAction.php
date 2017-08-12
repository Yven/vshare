<?php

namespace Src\Action;

use Src\Model\Admin;

class AdminAction extends BaseAction
{
    private $_admin;

    protected $_container;

    public function __construct(\Slim\Container $container)
    {
        // init the parent class
        parent::__construct($container->get('response'));
        $this->_container = $container;
        $this->_admin = new Admin();
    }

    public function signup($request, $response, $args)
    {
        try{
            $res = $this->_admin->signup($request->getParsedBody());
        } catch (\Exception $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$status['code']] : $e->getMessage()
            );
        }

        return $this->success($res, 201);
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
        // check the user's info
        try{
            $res = $this->_admin->login($request->getParsedBody());
        } catch (\Exception $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$status['code']] : $e->getMessage()
            );
        }

        // success
        $this->cookie($this->_container['newcookie'], ['token' => $this->_admin->getJWT()]);
        return $this->success($res);
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
        // get info
        try {
            $res = $this->_admin->getInfo($this->_container['cookie']->get('token'));
        } catch (\Expcetion $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$status['code']] : $e->getMessage()
            );
        }

            return $this->success($res);
    }
}
