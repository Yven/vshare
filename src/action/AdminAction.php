<?php

namespace Src\Action;

use Src\Model\Admin;
use Firebase\JWT\JWT;
use Src\Config;

class AdminAction extends BaseAction
{
    private $_admin;
    private $_rootLevel = [
        '7' => 'Administrator',
        '6' => 'Super Man',
        '5' => 'Manager',
        '4' => 'User',
    ];

    protected $_container;

    public function __construct(\Slim\Container $container) {
        $this->_container = $container;
        $this->_admin = new Admin();
    }

    public function signup()
    {
    }

    /**
     * set JWT's format.
     *
     * @param array  $data
     * @param string $token
     * @param string $key
     *
     * @return string
     */
    private function SetJWT($data, $token, $key)
    {
        $token = array_merge($token, [
            'iat' => $_SERVER['REQUEST_TIME'],
            'exp' => $_SERVER['REQUEST_TIME'] + 604800,
            'aud' => $data['username'],
            'logInAs' => $this->_rootLevel[$data['root']],
        ]);

        return JWT::encode($token, $key);
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
        // get data
        $data = $request->getParsedBody();

        // data exist
        if (isset($data['username']) && !empty($data['username'])) {
            // get real data
            $res = $this->_admin->from()->where('username', $data['username'])->fetch();

            // admin exist
            if (!empty($res)) {
                // password exist
                if (isset($data['passwd']) && !empty($data['passwd'])) {
                    // password verify success
                    if (password_verify($data['passwd'], $res['passwd'])) {
                        unset($res['passwd']);

                        // set jwt token
                        $jwt = $this->setJWT($res, Config::get('jwt'), Config::get('secret'));
                        // set root level message
                        $res['root'] = $this->_rootLevel[$res['root']];

                        return $this->successResponse($res, ['Set-Cookie' => 'token = '.$jwt.';']);
                    }
                }
            }

            return $this->errorResponse(422, 'User or Password Error!');
        } else {
            return $this->errorResponse(400);
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
        // get token
        $token = $_COOKIE['token'];
        $jwt = (array) JWT::decode($token, Config::get('secret'), array('HS256'));

        // get the admin that jwt write
        $res = $this->_admin->from()->where('username', $jwt['aud'])->fetch();
        // admin do not exist OR iss error OR overdue
        if (empty($res) || $jwt['iss'] !== Config::get('jwt')['iss'] || $jwt['exp'] < $_SERVER['REQUEST_TIME']) {
            return $this->errorResponse(401, 'Bad credentials');
        }
        unset($res['passwd']);
        $res['root'] = $jwt['logInAs'];

        return $this->successResponse($res);
    }
}
