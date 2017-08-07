<?php

namespace Src\Model;

// use \


class Admin extends \FluentPDO
{
    private $setting = [
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => '0212',
        'dbname' => 'vshare'
    ];

    private $_prefix = 'v_';
    private $_table;

    public function __construct(){
        // 初始化FPDO
        $pdo = new \PDO("mysql:host=".$this->setting['host'].";dbname=".$this->setting['dbname'], $this->setting['user'], $this->setting['pass']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        parent::__construct($pdo);
        $reflection = new \ReflectionClass($this);
        $this->_table = strtolower($reflection->getShortName());
    }

    public function getTable(){
        return $this->_prefix . $this->_table;
    }

    public function vInsertInto ($values = array()) {
        return $this->insertInto($this->getTable(), $values);
    }

    public function vFrom ($primaryKey = null) {
        return $this->from($this->getTable(), $primaryKey);
    }

    public function vUpdate($set = array(), $primaryKey = null){
        return $this->update($this->getTable(), $set, $primaryKey);
    }

    public function vDeleteFrom($primaryKey = null){
        return $this->deleteFrom($this->getTable(), $primaryKey);
    }

    public function test(){
        $query = $this->from("v_admin")->where("id > 7");
        foreach ($query as $value) {
            echo $value['id'];
        }
    }
}
