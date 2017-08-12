<?php

namespace Src\Model;

use Firebase\JWT\JWT;
use Src\Config;

class Admin extends Model
{
    private $_jwt;
    private $_rootLevel = [
        '7' => 'Administrator',
        '6' => 'Super Man',
        '5' => 'Manager',
        '4' => 'User',
    ];

    /**
     * the table's default field
     *
     * @var array
     */
    protected $_default = [
        "root" => 4,
        "favicon" => "http://"
    ];

    public function __construct()
    {
        parent::__construct();
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
     * sign up
     *
     * @param array $data
     * @return array|null
     */
    public function signup($data)
    {
        // check data
        if (!isset($data['username']) || empty($data['username']) ||
            !isset($data['passwd']) || empty($data['passwd'])) {
            throw new \Exception('', 400);
        }

        // if username has exist
        if (empty($this->from()->where('username', $data['username'])->fetch())) {
            throw new \Exception('username has exist!', 422);
        }

        // password disagree
        if ($data['passwd2'] !== $data['passwd']) {
            throw new \Exception("password disagree!", 422);
        }

        $res = $this->insertInto()->value($data)->execute();
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
            throw new \Exception("", 400);
        }
        // get real data
        $res = $this->from()->where('username', $data['username'])->fetch();

        // admin exist then password verify success
        if (empty($res) || !password_verify($data['passwd'], $res['passwd'])) {
            throw new \Exception('Username or Password Error!', 422);
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
            throw new \Exception("", 401);
        }
        // decode the token
        $jwt = (array) JWT::decode($token, Config::get('secret'), array('HS256'));

        // get the admin that jwt write
        $res = $this->from()->where('username', $jwt['aud'])->fetch();
        // admin do not exist OR iss error OR overdue
        if (empty($res) || $jwt['iss'] !== Config::get('jwt')['iss'] || $jwt['exp'] < $_SERVER['REQUEST_TIME']) {
            $this->_code = 401;

            return null;
            throw new \Exception("", 401);
        }

        unset($res['passwd']);
        $res['root'] = $jwt['logInAs'];

        return $res;
    }
}
