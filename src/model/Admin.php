<?php

namespace Src\Model;

use Firebase\JWT\JWT;
use Src\Config;

class Admin extends \FluentPDO
{
    private $_code;
    private $_jwt;
    private $_message = '';
    private $_rootLevel = [
        '7' => 'Administrator',
        '6' => 'Super Man',
        '5' => 'Manager',
        '4' => 'User',
    ];

    public function __construct()
    {
        // 初始化FPDO
        parent::__construct(Config::get('db'));
        $this->_code = 200;
    }

    /**
     * get the result info.
     *
     * @return array
     */
    public function getStatus()
    {
        return [
            'code' => $this->_code,
            'message' => $this->_message,
        ];
    }

    /**
     * get JWT token.
     *
     * @return string
     */
    public function getJWT()
    {
        return $this->_jwt;
    }

    /**
     * set JWT's format.
     *
     * @param array  $data
     * @param string $token
     * @param string $key
     */
    private function SetJWT($data, $token, $key)
    {
        $token = array_merge($token, [
            'iat' => $_SERVER['REQUEST_TIME'],
            'exp' => $_SERVER['REQUEST_TIME'] + 604800,
            'aud' => $data['username'],
            'logInAs' => $this->_rootLevel[$data['root']],
        ]);

        $this->_jwt = JWT::encode($token, $key);
    }

    /**
     * check the identity.
     *
     * @param array $data
     *
     * @return array|null
     */
    public function login($data)
    {
        // data exist
        if (!isset($data['username']) || empty($data['username']) ||
            !isset($data['passwd']) || empty($data['passwd'])) {
            $this->_code = 400;

            return null;
        }
        // get real data
        $res = $this->from()->where('username', $data['username'])->fetch();

        // admin exist then password verify success
        if (empty($res) || !password_verify($data['passwd'], $res['passwd'])) {
            $this->_code = 422;
            $this->_message = 'Username or Password Error!';

            return null;
        }
        unset($res['passwd']);

        // set jwt token
        $this->setJWT($res, Config::get('jwt'), Config::get('secret'));
        // set root level message
        $res['root'] = $this->_rootLevel[$res['root']];

        return $res;
    }

    /**
     * get the admin info that has been logined.
     *
     * @param string $token
     *
     * @return array|null
     */
    public function getInfo($token)
    {
        // token do not exist
        if (empty($token)) {
            $this->_code = 401;

            return null;
        }
        // decode the token
        $jwt = (array) JWT::decode($token, Config::get('secret'), array('HS256'));

        // get the admin that jwt write
        $res = $this->from()->where('username', $jwt['aud'])->fetch();
        // admin do not exist OR iss error OR overdue
        if (empty($res) || $jwt['iss'] !== Config::get('jwt')['iss'] || $jwt['exp'] < $_SERVER['REQUEST_TIME']) {
            $this->_code = 401;

            return null;
        }

        unset($res['passwd']);
        $res['root'] = $jwt['logInAs'];

        return $res;
    }
}
