<?php

namespace Src\Action;

use Src\Model\Admin;

class AdminAction extends Action
{
    private $_admin;

    protected $_container;

    /**
     * construct.
     *
     * @param \Slim\Container $container
     */
    public function __construct(\Slim\Container $container)
    {
        // init the parent class
        parent::__construct($container->get('response'));
        $this->_container = $container;
        $this->_admin = new Admin();
    }

    /**
     * signup.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function signup($request, $response, $args)
    {
        try {
            $res = $this->_admin->signup($request->getParsedBody());
        } catch (\Exception $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$e->getCode()] : $e->getMessage()
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
        try {
            $res = $this->_admin->login($request->getParsedBody());
        } catch (\Exception $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$e->getCode()] : $e->getMessage()
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
                empty($e->getMessage()) ? $this->_ERR_MSG[$e->getCode()] : $e->getMessage()
            );
        }

        return $this->success($res);
    }

    /**
     * edit admin info.
     *
     * @param Request  $requrest
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function editInfo($request, $response, $args)
    {
        // TODO
        if (false === $request->getAttribute('csrf_status')) {
            return $this->error(401,$this->_ERR_MSG[401]);
        }

        try {
            $res = $this->_admin->editInfo($args['id'], $request->getParsedBody());
        } catch (\Exception $e) {
            return $this->error(
                $e->getCode(),
                empty($e->getMessage()) ? $this->_ERR_MSG[$e->getCode()] : $e->getMessage()
            );
        }

        return $this->success($res);
    }

    public function permissionCheck()
    {
    }
}
