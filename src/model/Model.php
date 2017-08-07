<?php

namespace Src\Model;

class Model
{
    private $_query;


    public function select(){
        $this->_query = "select ";
        return $this;
    }

    public function where($map){
        foreach ($map as $field => $del) {
            foreach ($del as $opera => $value) {
                $q = '`' . $field . '` ' . $opera . ' ' . $value . ' ';
            }
        }

        $this->_query = "";
    }

    public function field($field){
    }

    public function from($table){
    }

    public function order($param, $rule){
    }

}
