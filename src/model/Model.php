<?php

namespace Src\Model;

use Src\Config;

class Model extends \FluentPDO
{
    public function __construct()
    {
        // 初始化FPDO
        parent::__construct(Config::get('db'));
        $this->setDefault();
        $this->setTime();
    }

    /**
     * get the fields.
     */
    private function setDefault()
    {
        if (isset($this->_default) && !empty($this->_default) && is_array($this->_default)) {
            foreach ($value as $k => $v) {
                // if is not the field
                if (!in_array($k, $this->field)) {
                    unset($value[$k]);
                }
            }
        } else {
            $this->_default = [];
        }
    }

    /**
     * auto set the time
     *
     * @return void
     */
    private function setTime()
    {
        // set create time
        if (isset($this->_autoTime) && !empty($this->_autoTime) && in_array($this->_autoTime, $this->field)) {
            $this->_default[$this->_autoTime] = time();
        }
        // set update time
        if (isset($this->_autoUpdate) && !empty($this->_autoUpdate) && in_array($this->_autoUpdate, $this->field)) {
            $this->_default[$this->_autoUpdate] = time();
        }
    }
}
