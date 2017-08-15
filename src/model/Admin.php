<?php

namespace Src\Model;

use Firebase\JWT\JWT;
use Src\Config;
use Src\Validate\Validate;

class Admin extends Model
{
    /** @var string jwt token vlaue */
    private $_jwt;
    /** @var array root to string */
    private $_rootLevel = [
        '7' => 'Administrator',
        '6' => 'Super Man',
        '5' => 'Manager',
        '4' => 'User',
    ];

    /** @var Validate validate instance */
    private $_validate;
    /** @var array validate's default rules */
    private $_rules = [
        'require' => ['username', 'passwd'],
        'length' => ['username' => '4,20', 'root' => '4,7'],
    ];

    /** @var array the table's default field. */
    protected $_default = [
        'root' => 4,
        'favicon' => 'http://',
    ];

    /** @var string set creat time automtic */
    protected $_autoTime = 'create_at';
    /** @var string set update time automtic */
    protected $_autoUpdate = 'update_at';

    /**
     * construct.
     */
    public function __construct()
    {
        parent::__construct();
        // construct validate
        $this->_validate = new Validate($this, $this->_rules);
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
     * sign up.
     *
     * @param array $data
     *
     * @return array|null
     */
    public function signup($data)
    {
        // check data
        $this->_validate->add([
            'length' => ['passwd' => '6,20'],
            'passcheck' => ['passwd', 'passwd2'],
        ])->check($data);

        // password encry
        $data['passwd'] = password_hash($data['passwd'], PASSWORD_DEFAULT);

        try {
            // if username has exist
            if (!empty($this->from()->where('username', $data['username'])->fetch())) {
                throw new \Exception('username has exist!', 422);
            }

            // insert and get the new admin info
            if (($res = $this->insertInto()->field()->values($data)->execute())) {
                $info = $this->from()->where('id', $res)->fetch();
                unset($info['passwd']);
            } else {
                throw new \Exception('insert failed', 500);
            }
        } catch (\PDOException $e) {
            // sql query error, default 422 error code
            throw new \Exception($e->getMessage(), 422);
        }

        return $info;
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
        $this->_validate->check($data);
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

    public function updateInfo()
    {
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
            throw new \Exception('', 401);
        }
        // decode the token
        $jwt = (array) JWT::decode($token, Config::get('secret'), array('HS256'));

        // get the admin that jwt write
        $res = $this->from()->where('username', $jwt['aud'])->fetch();
        // admin do not exist OR iss error OR overdue
        if (empty($res) || $jwt['iss'] !== Config::get('jwt')['iss'] || $jwt['exp'] < $_SERVER['REQUEST_TIME']) {
            $this->_code = 401;

            return null;
            throw new \Exception('', 401);
        }

        unset($res['passwd']);
        $res['root'] = $jwt['logInAs'];

        return $res;
    }
}
